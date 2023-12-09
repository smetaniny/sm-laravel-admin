<?php

namespace Smetaniny\SmLaravelAdmin\Console;

use Illuminate\Console\Command;

class PublishCommand extends Command
{
    /**
     * Сигнатура консольной команды.
     *
     * @var string
     */
    protected $signature = 'sm-laravel-admin:publish {--force : Перезаписать все существующие файлы}';

    /**
     * Описание консольной команды.
     *
     * @var string
     */
    protected $description = 'Опубликуйте все ресурсы sm-laravel-admin';

    /**
     * Обработка выполнения консольной команды.
     *
     * @return void
     */
    public function handle()
    {
        // Вызов команды 'vendor:publish' для публикации ресурсов (assets) с тегом 'sm-laravel-admin.assets'.
        $this->call('vendor:publish', [
            '--tag' => 'smetaniny.sm-laravel-admin',
            '--force' => true,
        ]);
    }
}
