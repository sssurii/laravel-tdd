<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Validator;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
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


    protected static $rules = [
        'name' => 'unique:users|required|min:3',
        'email' => 'required|email',
        'password' => 'required|min:6',
    ];

    protected $errors;

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            return $model->validate($model->attributes);
        });
    }

    public function validate($data)
    {
        $validator = Validator::make($data, self::$rules);

        if ($validator->fails()) {
            $this->errors = $validator->errors()->toArray();
            return false;
        }

        return true;
    }

    public function getErrors()
    {
        return $this->errors;
    }
}
