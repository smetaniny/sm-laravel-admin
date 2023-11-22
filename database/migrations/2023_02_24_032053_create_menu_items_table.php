<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * @return void
     */
    public function up()
    {
        Schema::create('menu_items', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('Название элемента меню');
            $table->string('slug')->comment('Уникальный идентификатор элемента меню');
            $table->string('icon')->nullable()->comment('Название иконки для элемента меню');
            $table->integer('order')->comment('Порядок сортировки элементов меню');
            $table->timestamps();

            $table->unsignedBigInteger('parent_id')->nullable()->comment('Родительский элемент меню');
            $table->foreign('parent_id')->references('id')->on('menu');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('menu_items');
    }
};
