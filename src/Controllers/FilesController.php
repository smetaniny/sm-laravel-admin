<?php

namespace Smetaniny\SmLaravelAdmin\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class FilesController extends Controller
{
    public function fileProcessing($filePath, Request $request)
    {
        $filePath = str_replace(';', '/', $filePath);

        // Получите загруженный файл из запроса
        $file = $request->file('file');

        // Проверьте наличие файла и свойства "filePath"
        if (!$file || !$filePath) {
            return response()->json(['error' => 'Не передан файл или путь (filePath) для сохранения файла'], 400);
        }

        // Создайте экземпляр Intervention Image из загруженного файла
        $image = Image::make($file);

        // Получите оригинальное имя файла
        $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);

        // Удалите все символы, кроме латинских букв, цифр, точки и подчеркивания
        $fileName = preg_replace('/[^A-Za-z\d_.-]/u', '', $originalName);

        // Преобразуйте имя файла в безопасный формат
        $fileName = Str::slug($fileName);

        // Преобразуйте имя файла в нижний регистр
        $fileName = mb_strtolower($fileName, 'UTF-8');

        // Сохраните файл внутри storage с указанным именем и путем
        $storedPath = $file->storeAs('public/' . $filePath, $fileName . '.' . $file->getClientOriginalExtension());

        // Преобразуйте изображение в формат WebP и сохраните
        $image->encode('webp', 100)->save(storage_path('app/public/' . $filePath . '/' . $fileName . '.webp'));

        // Проверим ширину исходного изображения
        $originalWidth = $image->getWidth();

//        if ($originalWidth > 400) {
//            // Нарезайте изображение на версии с разными размерами и форматом WebP
//            $image->resize(400, null, function ($constraint) {
//                $constraint->aspectRatio();
//            })->encode('webp', 100)->save(storage_path('app/public/' . $filePath . '/' . $fileName . '-400.webp'));
//        }
//
//        if ($originalWidth > 640) {
//            $image->resize(640, null, function ($constraint) {
//                $constraint->aspectRatio();
//            })->encode('webp', 100)->save(storage_path('app/public/' . $filePath . '/' . $fileName . '-640.webp'));
//        }
//
//        if ($originalWidth > 920) {
//            $image->resize(920, null, function ($constraint) {
//                $constraint->aspectRatio();
//            })->encode('webp', 100)->save(storage_path('app/public/' . $filePath . '/' . $fileName . '-920.webp'));
//        }
//
//        if ($originalWidth > 1280) {
//            $image->resize(1280, null, function ($constraint) {
//                $constraint->aspectRatio();
//            })->encode('webp', 100)->save(storage_path('app/public/' . $filePath . '/' . $fileName . '-1280.webp'));
//        }
//
//        if ($originalWidth > 1600) {
//            $image->resize(1600, null, function ($constraint) {
//                $constraint->aspectRatio();
//            })->encode('webp', 100)->save(storage_path('app/public/' . $filePath . '/' . $fileName . '-1600.webp'));
//        }
//
//        if ($originalWidth > 1920) {
//            $image->resize(1920, null, function ($constraint) {
//                $constraint->aspectRatio();
//            })->encode('webp', 100)->save(storage_path('app/public/' . $filePath . '/' . $fileName . '-1920.webp'));
//        }

        // Верните относительный путь сохраненного файла
        return response()->json(['file' => str_replace('public', '/storage', $storedPath)]);
    }
}
