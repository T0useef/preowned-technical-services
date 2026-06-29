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

    public function store(Request $request)
    {
        $validated = $this->validateQuotation($request);

        $quotation = DB::transaction(function () use ($validated) {
            $quotation = Quotation::create([
                'quotation_number' => $this->generateQuotationNumber(),
                'company_name' => $validated['company_name'],
                'quotation_date' => $validated['quotation_date'],
                'notes' => $validated['notes'] ?? null,
                'total_amount' => $this->calculateTotal($validated['items']),
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
                'quotation_date' => $validated['quotation_date'],
                'notes' => $validated['notes'] ?? null,
                'total_amount' => $this->calculateTotal($validated['items']),
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

    public function destroy(Quotation $quotation)
    {
        $this->deleteQuotationPdf($quotation->file_path);
        $quotation->delete();

        return response()->json([
            'message' => 'Quotation deleted successfully',
        ]);
    }

    private function validateQuotation(Request $request): array
    {
        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'quotation_date' => 'required|date',
            'notes' => 'nullable|string|max:2000',
            'items' => 'required|array|min:1',
            'items.*.description' => 'required|string|max:500',
            'items.*.qty' => 'required|numeric|min:0.01',
            'items.*.unit_price' => 'required|numeric|min:0',
        ]);

        if (empty($validated['items'])) {
            throw ValidationException::withMessages([
                'items' => 'At least one line item is required.',
            ]);
        }

        return $validated;
    }

    private function syncItems(Quotation $quotation, array $items): void
    {
        foreach ($items as $index => $item) {
            $qty = (float) $item['qty'];
            $unitPrice = (float) $item['unit_price'];

            $quotation->items()->create([
                'description' => $item['description'],
                'qty' => $qty,
                'unit_price' => $unitPrice,
                'total' => round($qty * $unitPrice, 2),
                'sort_order' => $index,
            ]);
        }
    }

    private function calculateTotal(array $items): float
    {
        $total = 0;

        foreach ($items as $item) {
            $total += round((float) $item['qty'] * (float) $item['unit_price'], 2);
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

        $validTill = Carbon::parse($quotation->quotation_date)->addDays(15);

        $pdf = Pdf::loadView('quotations.quotation-pdf', [
            'quotation' => $quotation,
            'validTill' => $validTill,
        ])->setPaper('a4', 'portrait');

        File::put($absoluteFilePath, $pdf->output());

        $quotation->update(['file_path' => $relativeFilePath]);
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
