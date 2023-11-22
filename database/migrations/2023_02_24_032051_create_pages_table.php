<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Smetaniny\SmLaravelAdmin\Models\Category;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pages', function (Blueprint $table) {
            $table->id();
            $table->string('title')->comment('Заголовок');
            $table->string('alias')->unique()->comment('Псевдоним');
            $table->integer('menuindex')->nullable()->comment('Позиция в меню');
            $table->string('menutitle')->nullable()->comment('Краткий заголовок');
            $table->string('slug')->unique()->nullable()->comment('URL-адрес');
            $table->string('language')->default('en')->comment('Язык (например, "ru", "en", "fr")');
            $table->string('controller_name')->nullable()->comment('Название контроллера');
            $table->longText('content_js')->nullable()->comment('Содержимое (в формате js)');
            $table->longText('content')->nullable()->comment('Содержимое (в формате HTML)');
            $table->text('description')->nullable()->comment('Метатег description (для SEO)');
            $table->text('meta_keywords')->nullable()->comment('Метатег keywords (для SEO)');
            $table->boolean('is_published')->default(false)->comment('Флаг, указывающий, опубликована ли страница');
            $table->boolean('is_visible_url')->default(false)->comment('Флаг, указывающий, участвия в URL');
            $table->boolean('is_open')->default(false)->comment('Флаг, указывающий, открытие папки в дереве');
            $table->timestamp('published_at')->nullable()->comment('Дата и время публикации');
            $table->timestamp('unpublished_at')->nullable()->comment('Дата и время снятия с публикации ');
            $table->string('canonical_url')->nullable()->comment('URL-адрес канонической версии');
            $table->string('og_title')->nullable()->comment('Метатег Open Graph title');
            $table->text('og_description')->nullable()->comment('Метатег Open Graph description');
            $table->string('og_image')->nullable()->comment('Метатег Open Graph image');
            $table->string('twitter_title')->nullable()->comment('Метатег Twitter title');
            $table->text('twitter_description')->nullable()->comment('Метатег Twitter description');
            $table->string('twitter_image')->nullable()->comment('Метатег Twitter image');
            $table->text('css')->nullable()->comment('CSS-код');
            $table->text('js')->nullable()->comment('JavaScript-код');
            $table->string('robots')->default('index,follow')->comment('Инструкции для поисковых роботов по индексации и индексации ссылок на странице. Например, значение noindex, nofollow запрещает индексацию и следование по ссылкам на ней');
            $table->float('sitemap_priority')->nullable()->default(0.5)->comment('Приоритет в файле sitemap.xml (от 0 до 1, где 1 - наибольший приоритет)');
            $table->string('sitemap_frequency')->nullable()->default('monthly')->comment('Частота обновления для поисковых роботов. Возможные значения: always, hourly, daily, weekly, monthly, yearly, never');
            $table->timestamps();

            $table->unsignedBigInteger('parent_id')->nullable()->comment('идентификатор родительской (если страница является дочерней, иначе null)');
            $table->unsignedBigInteger('comments_count')->default(0)->comment('количество комментариев к странице');
            $table->unsignedBigInteger('views_count')->default(0)->comment('количество просмотров');
            $table->unsignedBigInteger('likes_count')->default(0)->comment('количество лайков');
            $table->unsignedBigInteger('shares_count')->default(0)->comment(' количество раз, когда страница была поделена в социальных сетях или других платформах');
            $table->unsignedBigInteger('_lft')->nullable()->comment('значение левой границы');
            $table->unsignedBigInteger('_rgt')->nullable()->comment('значение правой границы');

            $table->unsignedBigInteger('template_id')->nullable();
            $table->foreign('template_id')->references('id')->on('templates')->onDelete('cascade');
            $table->foreignId('tv_param_id')->nullable()->constrained('tv_params')->onDelete('cascade')->comment('Связь с таблицей tv параметров страницы');

            //строки таблицы не будут удалены навсегда, а будут помечены как удаленные путем заполнения столбца deleted_at соответствующей датой
            $table->softDeletes()->comment('помечены как удаленные');
            //определяет столбец author_id как внешний ключ и связывает его с таблицей users
            $table->foreignId('author_id')->constrained('users_admin')->onDelete('cascade');
            //идентификатор категории
            $table->foreignIdFor(Category::class)->onDelete('cascade');
            //идентификатор пользователя, который последний раз обновлял страницу
            $table->foreignId('updated_by_id')->nullable()->constrained('users_admin')->onDelete('cascade');

            $table->index('title');
            $table->index('slug');
            $table->index('parent_id');
            $table->index('template_id');
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pages');
    }
};
