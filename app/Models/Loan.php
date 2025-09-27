<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    use HasFactory;

    protected $fillable = [
        'loan_number', 'member_id', 'type_of_loan', 'date_of_loan', 'interest_rate',
        'number_of_terms', 'date_loan_approved', 'monthly_payment'
    ];

    // 🔹 Define the relationship with User (Member)
    public function member()
    {
        return $this->belongsTo(User::class, 'member_id');
    }
}