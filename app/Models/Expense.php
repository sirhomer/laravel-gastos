<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    protected $fillable = [
        'user_id',
        'concept',
        'amount',
        'date',
        'installments',
        'is_paid',
        'is_recurring',
    ];

    protected $casts = [
        'is_paid' => 'boolean',
        'is_recurring' => 'boolean',
        'amount' => 'decimal:2',
        'date' => 'date',
    ];
}
