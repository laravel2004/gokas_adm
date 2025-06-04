<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    protected  $guarded = ['id'];

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function employee()
    {
        return $this->hasOneThrough(
            Employee::class,
            Account::class,
            'id',
            'id',
            'account_id',
            'employee_id'
        );
    }

    public function approval()
    {
        return $this->belongsTo(Approval::class);
    }
}
