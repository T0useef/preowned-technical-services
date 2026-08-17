<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\ExpenseReceipt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class ExpenseController extends Controller
{
    public function index()
    {
        $expenses = Expense::with('receipts')->orderByDesc('expense_date')->orderByDesc('id')->get();

        return view('expenses.index', [
            'expenses' => $expenses,
        ]);
    }

    public function show(Expense $expense)
    {
        $expense->load('receipts');

        return response()->json([
            'data' => $this->formatExpense($expense),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'expense_date' => 'required|date',
            'price' => 'required|numeric|min:0',
            'description' => 'required|string|max:2000',
            'receipts' => 'required|array|min:1',
            'receipts.*' => 'file|mimes:jpg,jpeg,png,webp,pdf|max:5120',
        ]);

        $expense = Expense::create([
            'expense_date' => $validated['expense_date'],
            'price' => $validated['price'],
            'description' => $validated['description'],
        ]);

        foreach ($request->file('receipts', []) as $file) {
            $stored = $this->storeReceiptFile($file, $validated['expense_date']);
            $expense->receipts()->create($stored);
        }

        $expense->load('receipts');

        return response()->json([
            'message' => 'Expense created successfully',
            'data' => $this->formatExpense($expense),
        ], 201);
    }

    public function update(Request $request, Expense $expense)
    {
        $validated = $request->validate([
            'expense_date' => 'required|date',
            'price' => 'required|numeric|min:0',
            'description' => 'required|string|max:2000',
            'receipts' => 'nullable|array',
            'receipts.*' => 'file|mimes:jpg,jpeg,png,webp,pdf|max:5120',
            'removed_receipt_ids' => 'nullable|array',
            'removed_receipt_ids.*' => 'integer|exists:expense_receipts,id',
        ]);

        $removedIds = collect($validated['removed_receipt_ids'] ?? [])
            ->map(fn ($id) => (int) $id)
            ->unique()
            ->values();

        if ($removedIds->isNotEmpty()) {
            $receiptsToDelete = $expense->receipts()->whereIn('id', $removedIds)->get();
            foreach ($receiptsToDelete as $receipt) {
                $this->deleteReceiptFile($receipt->file_path);
                $receipt->delete();
            }
        }

        foreach ($request->file('receipts', []) as $file) {
            $stored = $this->storeReceiptFile($file, $validated['expense_date']);
            $expense->receipts()->create($stored);
        }

        if ($expense->receipts()->count() === 0) {
            throw ValidationException::withMessages([
                'receipts' => 'At least one receipt is required.',
            ]);
        }

        $expense->update([
            'expense_date' => $validated['expense_date'],
            'price' => $validated['price'],
            'description' => $validated['description'],
        ]);

        $expense->load('receipts');

        return response()->json([
            'message' => 'Expense updated successfully',
            'data' => $this->formatExpense($expense),
        ]);
    }

    public function destroy(Expense $expense)
    {
        foreach ($expense->receipts as $receipt) {
            $this->deleteReceiptFile($receipt->file_path);
        }

        $expense->delete();

        return response()->json([
            'message' => 'Expense deleted successfully',
        ]);
    }

    private function storeReceiptFile($file, string $expenseDate): array
    {
        $year = date('Y', strtotime($expenseDate));
        $storageDir = public_path('Expenses/' . $year);
        File::ensureDirectoryExists($storageDir);

        $extension = strtolower($file->getClientOriginalExtension() ?: $file->extension());
        $fileName = Str::uuid()->toString() . ($extension ? '.' . $extension : '');
        $file->move($storageDir, $fileName);

        return [
            'file_path' => 'Expenses/' . $year . '/' . $fileName,
            'original_name' => $file->getClientOriginalName(),
        ];
    }

    private function deleteReceiptFile(?string $path): void
    {
        if (!$path) {
            return;
        }

        $absolutePath = public_path($path);
        if (File::exists($absolutePath)) {
            File::delete($absolutePath);
        }
    }

    private function formatExpense(Expense $expense): array
    {
        return [
            'id' => $expense->id,
            'expense_date' => $expense->expense_date->format('Y-m-d'),
            'price' => number_format((float) $expense->price, 2, '.', ''),
            'description' => $expense->description,
            'receipts' => $expense->receipts->map(function (ExpenseReceipt $receipt) {
                return [
                    'id' => $receipt->id,
                    'name' => $receipt->original_name,
                    'url' => asset($receipt->file_path),
                    'is_pdf' => $this->receiptIsPdf($receipt->original_name, $receipt->file_path),
                ];
            })->values()->all(),
        ];
    }

    private function receiptIsPdf(?string $name, ?string $path): bool
    {
        $reference = strtolower((string) ($name ?: $path));

        return str_ends_with($reference, '.pdf');
    }
}
