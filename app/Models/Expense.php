<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Expense extends Model
{
    protected $fillable = [
        'expense_date',
        'price',
        'description',
    ];

    protected function casts(): array
    {
        return [
            'expense_date' => 'date',
            'price' => 'decimal:2',
        ];
    }

    public function receipts(): HasMany
    {
        return $this->hasMany(ExpenseReceipt::class);
    }
}
