<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoanPayment extends Model
{
    use HasFactory;

    // IMPORTANT: make sure this matches the table you showed in phpMyAdmin
    protected $table = 'loan_payments';   // <- change if your table name is different

    protected $primaryKey = 'id';

    protected $fillable = [
        'loan_id',
        'total_payments_count',
        'total_payments',
        'outstanding_balance',
        'latest_remittance',
        'date_of_remittance',
        'remittance_no',
        'date_covered_month',
        'date_covered_year',
    ];

    protected $casts = [
        'total_payments_count' => 'integer',
        'total_payments'       => 'decimal:2',
        'outstanding_balance'  => 'decimal:2',
        'latest_remittance'    => 'date',
        'date_of_remittance'   => 'date',
    ];
}
