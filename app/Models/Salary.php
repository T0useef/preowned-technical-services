<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Salary extends Model
{
    protected $fillable = [
        'user_id',
        'generated_by_user_id',
        'month',
        'year',
        'allowed_off',
        'monthly_salary',
        'final_salary',
        'advance_deduction',
        'payable_salary',
        'file_path',
        'generated_at',
    ];

    protected $casts = [
        'monthly_salary' => 'decimal:2',
        'final_salary' => 'decimal:2',
        'advance_deduction' => 'decimal:2',
        'payable_salary' => 'decimal:2',
        'generated_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
