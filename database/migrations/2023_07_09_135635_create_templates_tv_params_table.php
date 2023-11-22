<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('templates_tv_params', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('template_id')->comment('Идентификатор страницы, к которой относится параметр');
            $table->foreign('template_id')->references('id')->on('templates')->onDelete('cascade')->comment('Связь с таблицей страниц');

            $table->unsignedBigInteger('tv_param_id')->comment('Идентификатор параметра');
            $table->foreign('tv_param_id')->references('id')->on('tv_params')->onDelete('cascade')->comment('Связь с таблицей параметров');

            $table->unsignedBigInteger('page_id')->comment('Идентификатор страницы, к которой относится параметр');
            $table->foreign('page_id')->references('id')->on('pages')->onDelete('cascade')->comment('Связь с таблицей страниц');

            $table->text('value')->nullable()->comment('Содержимое параметра');

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
        Schema::dropIfExists('templates_tv_params');
    }
};
