<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User;

class Group extends Model
{
    use HasFactory;
    protected $table='groups';
    protected $fillable = [
       'id', 'name'
    ];

    public function privileges()
    {
        return $this->belongsToMany(Privilege::class, 'group_privileges');
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
