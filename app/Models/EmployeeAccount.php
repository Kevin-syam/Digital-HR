<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

class EmployeeAccount extends Model
{
    use HasFactory;

    const BANK_ACCOUNT_TYPE = ['saving', 'current', 'salary'];

    public $timestamps = false;

    protected $table = 'employee_accounts';

    protected $fillable = [
        'user_id',
        'bank_name',
        'bank_account_no',
        'bank_account_type',
        'salary'
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
