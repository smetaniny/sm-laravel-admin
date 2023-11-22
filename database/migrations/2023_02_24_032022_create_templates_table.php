<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('templates', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique()->comment('Имя шаблона');
            $table->string('alias')->unique()->comment('Псевдоним');
            $table->string('description')->nullable()->comment('Описание');
            $table->text('header_code')->nullable()->comment('Код, который должен быть вставлен в head страницы (например, для подключения внешних стилей, скриптов или счетчиков посещений)');
            $table->text('footer_code')->nullable()->comment('Код, который должен быть вставлен перед закрывающим тегом /body страницы (например, для подключения внешних скриптов или счетчиков посещений)');
            $table->timestamps();
        });

        DB::table('templates')->insert([
            'name' => 'Стандартный',
            'alias' => 'standartnyi',
            'description' => 'Стандартный шаблон страниц',
            'header_code' => null,
            'footer_code' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('templates');
    }
};
