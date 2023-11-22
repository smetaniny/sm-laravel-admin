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
        Schema::create('translations', function (Blueprint $table) {
            $table->id();
            $table->string('title')->comment('заголовок страницы на языке перевода');
            $table->string('language')->default('en')->comment('язык перевода (например, "ru", "en", "fr")');
            $table->timestamps();

            $table->foreignId('page_id')->constrained('pages')->onDelete('cascade');
        });

        $pageId = DB::table('pages')->where('slug', '/')->value('id');

        DB::table('translations')->insert([
            'title' => 'Главная страница',
            'language' => 'ru',
            'page_id' => $pageId,
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
        Schema::dropIfExists('translations');
    }
};
