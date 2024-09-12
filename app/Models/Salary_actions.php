<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class salary_actions extends Model
{
    use HasFactory;
    protected $fillable = ['id','date','type','amount','hours','details','employee_id','attendance_id'];
    public function attendance()
    {
        return $this->belongsTo(Attendance::class);
    }
}
