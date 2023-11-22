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
        Schema::create('menu', function (Blueprint $table) {
            $table->id()->comment('Идентификатор меню');
            $table->integer('parent_id')->nullable()->comment('Идентификатор родительской страницы');
            $table->string('title', 255)->comment('Заголовок страницы');
            $table->string('slug', 255)->comment('URL страницы');
            $table->integer('order')->default(0)->comment('Порядок сортировки');
            $table->boolean('is_enabled')->default(true)->comment('Включена ли страница');
            $table->integer('_lft')->nullable()->comment('Левая граница');
            $table->integer('_rgt')->nullable()->comment('Правая граница');
            $table->timestamps();

            $table->index('parent_id');

            $table->unsignedBigInteger('page_id')->nullable()->comment('Идентификатор страницы');
            $table->foreign('page_id')->references('id')->on('pages')->onDelete('cascade')->comment('Внешний ключ на таблицу pages');

        });

        $adminUsersId = DB::table('users_admin')->where('name', 'admin')->value('id');
        $categoriesPagesId = DB::table('categories')->where('slug', '/')->value('id');
        $templatesId = DB::table('templates')->where('alias', 'default')->value('id');

        DB::table('menu')->insert([
            'parent_id' => null,
            'title' => 'Главная страница',
            'slug' => '/',
            'order' => 0,
            'is_enabled' => true,
            'created_at' => now(),
            'updated_at' => now(),
            '_lft' => 1,
            '_rgt' => 2,
            'page_id' => DB::table('pages')->insertGetId([
                'parent_id' => null,
                'title' => 'Главная страница',
                'alias' => 'glavnaia-stranica',
                'slug' => '/',
                'controller_name' => 'MainController',
                'content' => '<h1>Добро пожаловать на главную страницу!</h1>',
                'author_id' => $adminUsersId,
                'category_id' => $categoriesPagesId,
                'template_id' => $templatesId,
                'language' => 'ru',
                'is_open' => true,
                'menuindex' => '0',
                '_lft' => 1,
                '_rgt' => 2,
                'created_at' => now(),
                'updated_at' => now()
            ]),
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('menu');
    }
};
