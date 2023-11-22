<?php

namespace Smetaniny\SmLaravelAdmin\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;


class UserAdmin extends Authenticatable
{
    use HasApiTokens, Notifiable;

    //Указываем таблицу с которой будем работать
    protected $table = 'users_admin';

    //Есть автоинкремент
    public $incrementing = true;

    //Автоматом писать дату добавления и обновления
    public $timestamps = true;

    //Указываем уникальное поле таблицы
    protected $primaryKey = 'id';


    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'is_active',
        'last_login_at',
        'phone',
        'address',
        'avatar',
        'bio',
        'social_links',
        'language',
        'timezone',
        'permissions',
        'custom_fields',
        'role_id'
    ];


    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
