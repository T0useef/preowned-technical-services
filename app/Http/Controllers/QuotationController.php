<?php

namespace App\Http\Controllers;

use App\Models\Quotation;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class QuotationController extends Controller
{
    public function index()
    {
        $quotations = Quotation::with('items')->latest()->get();

        return view('quotations.index', [
            'quotations' => $quotations,
        ]);
    }

    public function show(Quotation $quotation)
    {
        return response()->json([
            'data' => $quotation->load('items'),
            'file_url' => $quotation->file_path ? asset($quotation->file_path) : null,
        ]);
    }

    public function create()
    {
        return view('quotations.form', [
            'quotation' => null,
            'pageTitle' => 'Add Quotation',
        ]);
    }

    public function edit(Quotation $quotation)
    {
        $quotation->load('items');

        return view('quotations.form', [
            'quotation' => $quotation,
            'pageTitle' => 'Edit Quotation',
        ]);
    }

    public function store(Request $request)
    {
        $validated = $this->validateQuotation($request);

        $quotation = DB::transaction(function () use ($validated) {
            $quotation = Quotation::create([
                'quotation_number' => $this->generateQuotationNumber(),
                'company_name' => $validated['company_name'],
                'contact_person' => $validated['contact_person'] ?? null,
                'contact_phone' => $validated['contact_phone'] ?? null,
                'subject' => $validated['subject'] ?? null,
                'quotation_date' => $validated['quotation_date'],
                'notes' => $validated['notes'] ?? null,
                'total_amount' => $this->calculateTotal($validated['items']),
                'show_grand_total' => $validated['show_grand_total'] ?? true,
            ]);

            $this->syncItems($quotation, $validated['items']);
            $this->generateQuotationPdf($quotation);

            return $quotation->fresh('items');
        });

        return response()->json([
            'message' => 'Quotation created and PDF generated successfully',
            'data' => $quotation,
            'file_url' => asset($quotation->file_path),
        ], 201);
    }

    public function update(Request $request, Quotation $quotation)
    {
        $validated = $this->validateQuotation($request);

        $quotation = DB::transaction(function () use ($quotation, $validated) {
            $quotation->update([
                'company_name' => $validated['company_name'],
                'contact_person' => $validated['contact_person'] ?? null,
                'contact_phone' => $validated['contact_phone'] ?? null,
                'subject' => $validated['subject'] ?? null,
                'quotation_date' => $validated['quotation_date'],
                'notes' => $validated['notes'] ?? null,
                'total_amount' => $this->calculateTotal($validated['items']),
                'show_grand_total' => $validated['show_grand_total'] ?? true,
            ]);

            $quotation->items()->delete();
            $this->syncItems($quotation, $validated['items']);
            $this->generateQuotationPdf($quotation);

            return $quotation->fresh('items');
        });

        return response()->json([
            'message' => 'Quotation updated and PDF regenerated successfully',
            'data' => $quotation,
            'file_url' => asset($quotation->file_path),
        ]);
    }

    public function preview(Request $request)
    {
        $validated = $this->validateQuotation($request);

        $quotationNumber = $request->filled('quotation_number')
            ? $request->string('quotation_number')->toString()
            : $this->generateQuotationNumber();

        $quotation = $this->makePreviewQuotation($validated, $quotationNumber);
        $pdf = $this->makeQuotationPdf($quotation);

        return $pdf->stream('quotation-preview.pdf');
    }

    public function samplePreview()
    {
        $sampleItems = [
            ['item_type' => 'main_item', 'display_number' => '1', 'description' => 'Plumbing installation for ground floor washrooms', 'unit' => 'Job', 'qty' => 1, 'unit_price' => 4500],
            ['item_type' => 'main_item', 'display_number' => '2', 'description' => 'Electrical wiring and switchboard upgrade', 'unit' => 'Job', 'qty' => 1, 'unit_price' => 6250],
            ['item_type' => 'sub_heading', 'display_number' => '3', 'description' => 'HVAC Works', 'unit' => null, 'qty' => 0, 'unit_price' => 0],
            ['item_type' => 'sub_item', 'display_number' => '3.1', 'description' => 'HVAC duct cleaning and maintenance', 'unit' => 'Nos', 'qty' => 2, 'unit_price' => 2000],
            ['item_type' => 'sub_item', 'display_number' => '3.2', 'description' => 'Labour and site finishing works', 'unit' => 'Lot', 'qty' => 1, 'unit_price' => 4000],
        ];

        $validated = [
            'company_name' => 'M/s. DSCA Contracting Building LLC',
            'contact_person' => 'Ms. Dalia Abdullah Ghoush',
            'contact_phone' => '+971 52 738 2675',
            'subject' => 'Plumbing, electrical and HVAC maintenance works',
            'quotation_date' => now()->format('Y-m-d'),
            'notes' => 'Site survey completed. Materials and labour included as per agreed scope.',
            'items' => $sampleItems,
        ];

        $quotation = $this->makePreviewQuotation($validated, 'QUO-' . now()->format('Y') . '-0001');
        $pdf = $this->makeQuotationPdf($quotation);

        return $pdf->stream('quotation-sample-preview.pdf');
    }

    public function destroy(Quotation $quotation)
    {
        $this->deleteQuotationPdf($quotation->file_path);
        $quotation->delete();

        return response()->json([
            'message' => 'Quotation deleted successfully',
        ]);
    }

    public function downloadExcelTemplate()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Quotation Items');

        $headers = ['Item Type', 'Description', 'Unit', 'Qty', 'Unit Price', 'Total'];
        foreach ($headers as $index => $header) {
            $sheet->setCellValue([$index + 1, 1], $header);
        }

        $sampleRows = [
            ['main_item', 'Plumbing installation for ground floor washrooms', 'Job', 1, 4500, 4500],
            ['main_item', 'Electrical wiring and switchboard upgrade', 'Job', 1, 6250, ''],
            ['sub_heading', 'HVAC Works', '', '', '', ''],
            ['sub_item', 'HVAC duct cleaning and maintenance', 'Nos', 2, 2000, 4000],
            ['sub_item', 'Labour and site finishing works', 'Lot', 1, 4000, 'LS'],
        ];

        foreach ($sampleRows as $rowIndex => $row) {
            foreach ($row as $colIndex => $value) {
                $sheet->setCellValue([$colIndex + 1, $rowIndex + 2], $value);
            }
        }

        foreach (range('A', 'F') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        $writer = new Xlsx($spreadsheet);
        $tempPath = storage_path('app/quotation-items-template.xlsx');
        $writer->save($tempPath);

        return response()->download($tempPath, 'quotation-items-template.xlsx')->deleteFileAfterSend(true);
    }

    public function importExcel(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv|max:5120',
        ]);

        $spreadsheet = IOFactory::load($request->file('file')->getRealPath());
        $sheet = $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray(null, true, true, false);

        if (count($rows) < 2) {
            throw ValidationException::withMessages([
                'file' => 'The Excel file has no data rows.',
            ]);
        }

        $headerMap = $this->mapExcelHeaders($rows[0] ?? []);
        if (!isset($headerMap['item_type']) || !isset($headerMap['description'])) {
            throw ValidationException::withMessages([
                'file' => 'Excel must include "Item Type" and "Description" headers.',
            ]);
        }

        $rawItems = [];
        for ($i = 1; $i < count($rows); $i++) {
            $row = $rows[$i];
            if ($this->excelRowIsEmpty($row)) {
                continue;
            }

            $itemType = $this->normalizeExcelItemType($row[$headerMap['item_type']] ?? 'main_item');
            $description = trim((string) ($row[$headerMap['description']] ?? ''));
            if ($description === '') {
                continue;
            }

            $unit = isset($headerMap['unit']) ? trim((string) ($row[$headerMap['unit']] ?? '')) : '';
            $qty = isset($headerMap['qty']) ? $this->parseExcelCell($row[$headerMap['qty']] ?? null) : null;
            $unitPrice = isset($headerMap['unit_price']) ? $this->parseExcelCell($row[$headerMap['unit_price']] ?? null) : null;
            $total = isset($headerMap['total']) ? trim((string) ($row[$headerMap['total']] ?? '')) : '';

            if ($itemType === 'sub_heading') {
                $rawItems[] = [
                    'item_type' => 'sub_heading',
                    'description' => $description,
                    'unit' => null,
                    'qty' => '',
                    'unit_price' => '',
                    'total' => '',
                ];
                continue;
            }

            $qty = $qty ?? '';
            $unitPrice = $unitPrice ?? '';
            if ($total === '' && is_numeric($qty) && is_numeric($unitPrice)) {
                $total = number_format(round((float) $qty * (float) $unitPrice, 2), 2, '.', '');
            }

            $rawItems[] = [
                'item_type' => $itemType,
                'description' => $description,
                'unit' => $unit !== '' ? $unit : null,
                'qty' => $qty,
                'unit_price' => $unitPrice,
                'total' => $total,
            ];
        }

        if (empty($rawItems)) {
            throw ValidationException::withMessages([
                'file' => 'No valid item rows were found in the Excel file.',
            ]);
        }

        $items = $this->normalizeItems($rawItems);

        return response()->json([
            'message' => count($items) . ' item(s) imported successfully.',
            'items' => $items,
        ]);
    }

    private function mapExcelHeaders(array $headerRow): array
    {
        $map = [];

        foreach ($headerRow as $index => $header) {
            $key = strtolower(trim((string) $header));
            $key = preg_replace('/\s+/', ' ', $key);

            if (in_array($key, ['item type', 'item_type', 'type'], true)) {
                $map['item_type'] = $index;
            } elseif (in_array($key, ['description', 'item description'], true)) {
                $map['description'] = $index;
            } elseif ($key === 'unit') {
                $map['unit'] = $index;
            } elseif (in_array($key, ['qty', 'quantity'], true)) {
                $map['qty'] = $index;
            } elseif (in_array($key, ['unit price', 'unit_price', 'price'], true)) {
                $map['unit_price'] = $index;
            } elseif (in_array($key, ['total', 'amount'], true)) {
                $map['total'] = $index;
            }
        }

        return $map;
    }

    private function normalizeExcelItemType(mixed $value): string
    {
        $type = strtolower(trim((string) $value));
        $type = str_replace([' ', '-'], '_', $type);

        return match ($type) {
            'main_item', 'main', 'item' => 'main_item',
            'sub_heading', 'subheading', 'heading' => 'sub_heading',
            'sub_item', 'subitem', 'child' => 'sub_item',
            default => 'main_item',
        };
    }

    private function parseExcelCell(mixed $value): ?string
    {
        if ($value === null || $value === '') {
            return null;
        }

        $text = trim((string) $value);

        return $text === '' ? null : $text;
    }

    private function excelRowIsEmpty(array $row): bool
    {
        foreach ($row as $value) {
            if (trim((string) $value) !== '') {
                return false;
            }
        }

        return true;
    }

    private function validateQuotation(Request $request): array
    {
        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'contact_person' => 'nullable|string|max:255',
            'contact_phone' => 'nullable|string|max:50',
            'subject' => 'nullable|string|max:500',
            'quotation_date' => 'required|date',
            'notes' => 'nullable|string|max:2000',
            'show_grand_total' => 'nullable|boolean',
            'items' => 'required|array|min:1',
            'items.*.item_type' => 'nullable|in:main_item,sub_heading,sub_item',
            'items.*.display_number' => 'nullable|string|max:20',
            'items.*.description' => 'required|string|max:500',
            'items.*.unit' => 'nullable|string|max:50',
            'items.*.qty' => 'nullable|string|max:100',
            'items.*.unit_price' => 'nullable|string|max:100',
            'items.*.total' => 'nullable|string|max:100',
        ]);

        if (empty($validated['items'])) {
            throw ValidationException::withMessages([
                'items' => 'At least one line item is required.',
            ]);
        }

        $validated['items'] = $this->normalizeItems($validated['items']);
        $validated['show_grand_total'] = $request->boolean('show_grand_total', true);

        return $validated;
    }

    private function normalizeItems(array $items): array
    {
        $topLevel = 0;
        $subIndex = 0;
        $inHeading = false;
        $normalized = [];

        foreach (array_values($items) as $item) {
            $type = $item['item_type'] ?? 'main_item';
            if (!in_array($type, ['main_item', 'sub_heading', 'sub_item'], true)) {
                $type = 'main_item';
            }

            if ($type === 'sub_item' && !$inHeading) {
                $type = 'main_item';
            }

            if ($type === 'main_item' || $type === 'sub_heading') {
                $topLevel++;
                $subIndex = 0;
                $inHeading = $type === 'sub_heading';
                $displayNumber = (string) $topLevel;
            } else {
                $subIndex++;
                $displayNumber = $topLevel . '.' . $subIndex;
            }

            $isHeading = $type === 'sub_heading';
            $qty = $isHeading ? '' : trim((string) ($item['qty'] ?? ''));
            $unitPrice = $isHeading ? '' : trim((string) ($item['unit_price'] ?? ''));

            $rawTotal = trim((string) ($item['total'] ?? ''));
            if ($isHeading) {
                $totalValue = '';
            } elseif ($rawTotal === '' && is_numeric($qty) && is_numeric($unitPrice)) {
                $totalValue = number_format(round((float) $qty * (float) $unitPrice, 2), 2, '.', '');
            } else {
                $totalValue = $rawTotal;
            }

            $normalized[] = [
                'item_type' => $type,
                'display_number' => $displayNumber,
                'description' => $item['description'],
                'unit' => $isHeading ? null : ($item['unit'] ?? null),
                'qty' => $qty,
                'unit_price' => $unitPrice,
                'total' => $totalValue,
            ];
        }

        return $normalized;
    }

    private function syncItems(Quotation $quotation, array $items): void
    {
        foreach ($items as $index => $item) {
            $quotation->items()->create([
                'description' => $item['description'],
                'item_type' => $item['item_type'] ?? 'main_item',
                'display_number' => $item['display_number'] ?? null,
                'unit' => $item['unit'] ?? null,
                'qty' => (string) ($item['qty'] ?? ''),
                'unit_price' => (string) ($item['unit_price'] ?? ''),
                'total' => (string) ($item['total'] ?? ''),
                'sort_order' => $index,
            ]);
        }
    }

    private function calculateTotal(array $items): float
    {
        $total = 0;

        foreach ($items as $item) {
            if (($item['item_type'] ?? 'main_item') === 'sub_heading') {
                continue;
            }

            $lineTotal = trim((string) ($item['total'] ?? ''));
            if ($lineTotal === '' || !is_numeric($lineTotal)) {
                continue;
            }

            $total += round((float) $lineTotal, 2);
        }

        return round($total, 2);
    }

    private function generateQuotationPdf(Quotation $quotation): void
    {
        $quotation->load('items');

        $year = Carbon::parse($quotation->quotation_date)->format('Y');
        $storageDir = public_path('Quotations/' . $year);
        File::ensureDirectoryExists($storageDir);

        $fileName = Str::slug($quotation->quotation_number, '_') . '.pdf';
        $absoluteFilePath = $storageDir . DIRECTORY_SEPARATOR . $fileName;
        $relativeFilePath = 'Quotations/' . $year . '/' . $fileName;

        if ($quotation->file_path && $quotation->file_path !== $relativeFilePath) {
            $this->deleteQuotationPdf($quotation->file_path);
        }

        $pdf = $this->makeQuotationPdf($quotation);

        File::put($absoluteFilePath, $pdf->output());

        $quotation->update(['file_path' => $relativeFilePath]);
    }

    private function makePreviewQuotation(array $validated, string $quotationNumber): Quotation
    {
        $quotation = new Quotation([
            'quotation_number' => $quotationNumber,
            'company_name' => $validated['company_name'],
            'contact_person' => $validated['contact_person'] ?? null,
            'contact_phone' => $validated['contact_phone'] ?? null,
            'subject' => $validated['subject'] ?? null,
            'quotation_date' => $validated['quotation_date'],
            'notes' => $validated['notes'] ?? null,
            'total_amount' => $this->calculateTotal($validated['items']),
            'show_grand_total' => $validated['show_grand_total'] ?? true,
        ]);

        $items = collect($validated['items'])->values()->map(function (array $item, int $index) {
            $qty = (string) ($item['qty'] ?? '');
            $unitPrice = (string) ($item['unit_price'] ?? '');
            $total = (string) ($item['total'] ?? '');

            if ($total === '' && is_numeric($qty) && is_numeric($unitPrice)) {
                $total = number_format(round((float) $qty * (float) $unitPrice, 2), 2, '.', '');
            }

            return (object) [
                'description' => $item['description'],
                'item_type' => $item['item_type'] ?? 'main_item',
                'display_number' => $item['display_number'] ?? (string) ($index + 1),
                'unit' => $item['unit'] ?? null,
                'qty' => $qty,
                'unit_price' => $unitPrice,
                'total' => $total,
                'sort_order' => $index,
            ];
        });

        $quotation->setRelation('items', $items);

        return $quotation;
    }

    private function makeQuotationPdf(Quotation $quotation)
    {
        if ($quotation->exists) {
            $quotation->loadMissing('items');
        }

        $letterheadPath = public_path('template/Letterhead.png');
        $letterhead = 'data:image/png;base64,' . base64_encode(File::get($letterheadPath));

        $footerPages = [];
        foreach (['Footer-1.png', 'Footer-2.png'] as $footerFile) {
            $footerPath = public_path('template/' . $footerFile);
            if (File::exists($footerPath)) {
                $footerPages[] = 'data:image/png;base64,' . base64_encode(File::get($footerPath));
            }
        }

        $noteIcon = null;
        $notePngPath = public_path('template/note-icon.png');

        if (File::exists($notePngPath)) {
            $noteIcon = 'data:image/png;base64,' . base64_encode(File::get($notePngPath));
        }

        return Pdf::loadView('quotations.quotation-pdf', [
            'quotation' => $quotation,
            'letterhead' => $letterhead,
            'footerPages' => $footerPages,
            'noteIcon' => $noteIcon,
        ])->setPaper('a4', 'portrait');
    }

    private function deleteQuotationPdf(?string $filePath): void
    {
        if (!$filePath) {
            return;
        }

        $absolutePath = public_path($filePath);

        if (File::exists($absolutePath)) {
            File::delete($absolutePath);
        }
    }

    private function generateQuotationNumber(): string
    {
        $year = now()->format('Y');
        $prefix = 'QUO-' . $year . '-';

        $lastNumber = Quotation::where('quotation_number', 'like', $prefix . '%')
            ->orderByDesc('id')
            ->value('quotation_number');

        $sequence = 1;

        if ($lastNumber && preg_match('/-(\d+)$/', $lastNumber, $matches)) {
            $sequence = ((int) $matches[1]) + 1;
        }

        return $prefix . str_pad((string) $sequence, 4, '0', STR_PAD_LEFT);
    }
}
