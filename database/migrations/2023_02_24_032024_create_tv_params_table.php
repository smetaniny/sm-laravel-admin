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
            Schema::create('tv_params', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable()->comment('Имя параметра');
            $table->string('caption')->nullable()->comment('Заголовок параметра');
            $table->text('description')->nullable()->comment('Описание параметра');
            $table->string('type')->nullable()->comment('Тип параметра');
            $table->text('elements')->nullable()->comment('Элементы параметра');
            $table->text('default_text')->nullable()->comment('Значение по умолчанию (для текстовых параметров)');
            $table->float('default_number')->nullable()->comment('Значение по умолчанию (для числовых параметров)');
            $table->string('category')->nullable()->comment('Категория параметра');
            $table->string('input_type')->nullable()->comment('Тип ввода');
            $table->json('setting')->nullable()->comment('Настройки');
            $table->text('possible_values')->nullable()->comment('Возможные значения');
            $table->string('visual_component')->nullable()->comment('Визуальный компонент');
            $table->integer('order')->nullable()->comment('Порядок в списке');
            $table->timestamps();

            $table->unsignedBigInteger('tv_param_category_id')->nullable();
            $table->foreign('tv_param_category_id')->references('id')->on('tv_param_categories')->onDelete('set null');
            $table->index('tv_param_category_id');

            $table->unsignedBigInteger('tv_param_template_id')->nullable();
            $table->foreign('tv_param_template_id')->references('id')->on('templates')->onDelete('cascade');
            $table->index('tv_param_template_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tv_params');
    }
};
