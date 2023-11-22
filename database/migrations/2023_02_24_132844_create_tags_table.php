<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tags', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('название тега');
            $table->text('description')->nullable()->comment('описание тега');
            $table->timestamps();

            $table->index('name');
        });

        DB::table('tags')->insert([
            ['name' => 'Важное', 'description' => 'Важные материалы'],
            ['name' => 'Акции', 'description' => 'Информация о текущих акциях'],
            ['name' => 'События', 'description' => 'Новости о предстоящих событиях'],
            ['name' => 'Партнеры', 'description' => 'Информация о наших партнерах'],
            ['name' => 'Технологии', 'description' => 'Новости о технологиях и инновациях'],
            ['name' => 'Продукты', 'description' => 'Информация о нашей продукции'],
            ['name' => 'Карьера', 'description' => 'Вакансии и карьерные возможности'],
            ['name' => 'Награды', 'description' => 'Информация о наших наградах и достижениях'],
            ['name' => 'Обучение', 'description' => 'Информация об обучающих программах'],
            ['name' => 'Социальная ответственность', 'description' => 'Информация о нашей социальной ответственности'],
            ['name' => 'Научные исследования', 'description' => 'Новости исследований и научные открытия'],
            ['name' => 'Пресс-релизы', 'description' => 'Пресс-релизы о нашей компании и продуктах'],
            ['name' => 'Интервью', 'description' => 'Интервью с сотрудниками компании и экспертами'],
            ['name' => 'Финансовые отчеты', 'description' => 'Отчеты о финансовой деятельности компании'],
            ['name' => 'Конференции и выставки', 'description' => 'Новости о наших участиях в конференциях и выставках'],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tags');
    }
};
