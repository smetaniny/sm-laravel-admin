<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * @return void
     */
    public function up()
    {
        Schema::create('users_admin', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('Имя пользователя');
            $table->string('email')->unique()->comment('Адрес электронной почты пользователя');
            $table->string('password')->comment('Хеш пароля пользователя');
            $table->boolean('is_active')->default(true)->comment('Флаг активности пользователя');
            $table->timestamp('last_login_at')->nullable()->comment('Дата последнего входа пользователя');
            $table->string('phone')->nullable()->comment('Номер телефона пользователя');
            $table->string('address')->nullable()->comment('Адрес пользователя');
            $table->string('avatar')->nullable()->comment('Аватар пользователя');
            $table->text('bio')->nullable()->comment('Описание пользователя');
            $table->json('social_links')->nullable()->comment('Социальные ссылки пользователя');
            $table->string('language')->default('en')->comment('Язык пользователя');
            $table->string('timezone')->default('UTC')->comment('Временная зона пользователя');
            $table->json('permissions')->nullable()->comment('Разрешения пользователя');
            $table->json('custom_fields')->nullable()->comment('Пользовательские поля пользователя');
            $table->timestamps();

            $table->rememberToken()->comment('Токен для запоминания сеанса пользователя');

            // Это означает, что значение role_id в таблице users_admin должно существовать в таблице roles в качестве значения id.
            $table->unsignedBigInteger('role_id')->default(1)->comment('Идентификатор роли пользователя');
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
            $table->index('role_id');
        });

        DB::table('users_admin')->insert([
            'name' => 'admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('123admin@example.comqwerty')
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users_admin');
    }
};
