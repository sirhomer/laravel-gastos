<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExpenseInstallments extends Model
{
    protected $table = 'expense_installments';

    protected $fillable = [
        'expense_id',
        'installment_number',
        'amount',
        'month',
    ];
}
