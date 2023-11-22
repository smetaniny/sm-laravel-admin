<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
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
        /**
         * Таблица категорий
         */
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('название категории');
            $table->string('slug')->unique()->comment('уникальный идентификатор категории в URL');
            $table->text('description')->nullable()->comment('описание категории');
            $table->integer('nameorder')->nullable()->comment('Порядок сортировки');
            $table->integer('_lft')->nullable()->comment('Левая граница');
            $table->integer('_rgt')->nullable()->comment('Правая граница');
            $table->timestamps();

            $table->foreignId('parent_id')->nullable()->constrained('categories')->onDelete('cascade')->comment('ID родительской категории');

            $table->index('name');
            $table->index('slug');
            $table->index('parent_id');
        });



        DB::table('categories')->insert([
            [
                'name' => 'Главная',
                'slug' => '/',
                'description' => 'Категория для главной',
                'nameorder' => 1,
                'parent_id' => null,
                '_lft' => 1,
                '_rgt' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'О нас',
                'slug' => 'about',
                'description' => 'Категория для страниц о компании',
                'nameorder' => 2,
                'parent_id' => null,
                '_lft' => 3,
                '_rgt' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Услуги',
                'slug' => 'services',
                'description' => 'Категория для страниц с услугами"',
                'nameorder' => 3,
                'parent_id' => null,
                '_lft' => 5,
                '_rgt' => 6,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Блог',
                'slug' => 'blog',
                'description' => 'Категория для страниц блога',
                'nameorder' => 4,
                'parent_id' => null,
                '_lft' => 7,
                '_rgt' => 8,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Новости',
                'slug' => 'news',
                'description' => 'Категория для страниц новостей',
                'nameorder' => 5,
                'parent_id' => null,
                '_lft' => 9,
                '_rgt' => 10,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('categories');
    }
};
