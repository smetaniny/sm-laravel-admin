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
        Schema::create('alias', function (Blueprint $table) {
            $table->id();
            $table->string('url', 255);
            $table->timestamps();

            $table->unsignedBigInteger('page_id');
            $table->foreign('page_id')->references('id')->on('pages')->onDelete('cascade');

            $table->index('url');

        });

        // Получаем ID главной страницы
        $pageId = DB::table('pages')->where('slug', '/')->value('id');

        DB::table('alias')->insert([
            [
                'url' => '/index',
                'page_id' => $pageId
            ]
        ]);
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pages_alias');
    }
};
