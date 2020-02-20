<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Laravel\Passport\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;
    use SoftDeletes; 

    const RULES = [
        'name' => 'required',
        'state' => 'required|string', 
        'phone' => 'required|string',
        'email' => 'required|email', 
        'password' => 'required', 
        'c_password' => 'required|same:password', 
        'cover_image'  => 'nullable|string',
        'method' => 'required|in:create,update,delete',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'state', 'phone', 'email', 'password', 'cover_image'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $dates = ['deleted_at',];
}
