<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Privilege extends Model
{

    use HasFactory;
    protected $table='privileges';
    protected $fillable = [
       'id', 'name', 'description'
    ];

    public function groups()
    {
        return $this->belongsToMany(Group::class, 'group_privileges');
    }
}
