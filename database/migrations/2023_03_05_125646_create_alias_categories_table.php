<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('alias_categories', function (Blueprint $table) {
            $table->id();
            $table->string('slug', 255);
            $table->integer('_lft')->nullable()->comment('Левая граница');
            $table->integer('_rgt')->nullable()->comment('Правая граница');
            $table->timestamps();

            $table->unsignedInteger('parent_id')->nullable()->comment('ID родительской категории');

            $table->unsignedBigInteger('category_id')->unsigned();
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade')->onUpdate('cascade');

            $table->unsignedBigInteger('page_id')->nullable()->comment('Идентификатор страницы');
            $table->foreign('page_id')->references('id')->on('pages')->onDelete('cascade')->comment('Внешний ключ на таблицу pages');

            $table->index('slug');
        });

        // Получаем ID категории "Главная"
        $mainCategoryId = DB::table('categories')->where('slug', '/')->value('id');

        // Вставляем запись в таблицу alias_categories
        DB::table('alias_categories')->insert([
            'category_id' => $mainCategoryId,
            'parent_id' => null,
            'slug' => '/',
            '_lft' => 1,
            '_rgt' => 2,
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
        Schema::dropIfExists('alias_categories');
    }
};
