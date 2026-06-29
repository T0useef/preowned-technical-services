<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Quotation extends Model
{
    protected $fillable = [
        'quotation_number',
        'company_name',
        'quotation_date',
        'notes',
        'total_amount',
        'file_path',
    ];

    protected function casts(): array
    {
        return [
            'quotation_date' => 'date',
            'total_amount' => 'decimal:2',
        ];
    }

    public function items(): HasMany
    {
        return $this->hasMany(QuotationItem::class)->orderBy('sort_order');
    }
}
