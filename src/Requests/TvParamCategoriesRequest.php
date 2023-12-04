<?php

namespace Smetaniny\SmLaravelAdmin\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\Contracts\Validation\Validator;
use JetBrains\PhpStorm\ArrayShape;
use Smetaniny\SmLaravelAdmin\Models\TvParamCategory;


class TvParamCategoriesRequest extends FormRequest
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
    #[ArrayShape(['name' => "array"])] public function rules(): array
    {
        return [
            'name' => [
                'string', 'required', 'max:250', 'regex:/^[A-Za-z0-9А-Яа-яЁё\s\-()]+$/u',
                Rule::unique((new TvParamCategory)->getTable())->ignore($this->route('id'))
            ],
        ];
    }

    #[ArrayShape(['name.required' => "string", 'name.unique' => "string"])] public function messages(): array
    {
        return [
            'name.required' => 'Поле `Имя категории` должно быть строкой, обязательным для заполнения и не должно превышать 250 символов.',
            'name.unique' => 'Поле `Имя категории` должно быть уникальным.',
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
