<?php

namespace Smetaniny\SmLaravelAdmin\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;
use Illuminate\Contracts\Validation\Validator;
use JetBrains\PhpStorm\ArrayShape;


class TvParamsRequest extends FormRequest
{
    /**
     * Определите, авторизован ли пользователь для выполнения этого запроса.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return (bool) auth()->guard('admin')->user();
    }

    /**
     * Правила проверки, которые применяются к запросу.
     *
     * @return array
     */
    #[ArrayShape(['name' => "string[]", 'input_type' => "string[]"])] public function rules(): array
    {
        return [
            'name' => [
                'string', 'required', 'max:250', 'regex:/^[A-Za-z0-9А-Яа-яЁё\s\-()]+$/u'
            ],
            'input_type' => ['string', 'required', 'max:250', 'regex:/^[A-Za-z0-9А-Яа-яЁё\s\-()]+$/u',],
        ];
    }

    /**
     * Обработка ответа - выводим данные, которые не прошли проверку
     *
     * @param Validator $validator
     *
     * @throws ValidationException
     */
    protected function failedValidation(Validator $validator)
    {
        throw (new ValidationException($validator))
            ->errorBag($this->errorBag)
            ->redirectTo($this->getRedirectUrl())
            ->status(422);
    }
}
