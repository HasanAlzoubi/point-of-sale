<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laratrust\Traits\LaratrustUserTrait;


class User extends Authenticatable
{
    use HasFactory;
    use LaratrustUserTrait;
    use Notifiable;
    protected $appends=['full_name'];

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'image',
        'password',
    ];


    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    protected function getFirstNameAttribute($value){
        return ucfirst($value);
    }
    protected function getLastNameAttribute($value){
        return ucfirst($value);
    }

    protected function getFullNameAttribute() {
        return $this->first_name . ' ' . $this->last_name;
    }
}
