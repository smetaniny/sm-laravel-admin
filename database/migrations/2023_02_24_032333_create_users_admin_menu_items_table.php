<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Миграция связей
     * @return void
     */
    public function up()
    {
        Schema::create('users_admin_menu_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('users_admin_id')->constrained('users_admin')->onDelete('cascade')->comment('ID пользователя в системе');
            $table->foreignId('menu_items_id')->constrained('menu_items')->onDelete('cascade')->comment('ID элемента меню');
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users_admin_menu_items');
    }
};
