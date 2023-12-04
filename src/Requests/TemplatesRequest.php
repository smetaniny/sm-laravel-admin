<?php

namespace Smetaniny\SmLaravelAdmin\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\Rule;
use Smetaniny\SmLaravelAdmin\Models\Template;

class TemplatesRequest extends FormRequest
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
    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'min:3',
                'max:250',
                'regex:/^[A-Za-z0-9А-Яа-яЁё\s\-()]+$/u',
                Rule::unique((new Template)->getTable())->ignore($this->route('id'))
            ],

            'description' => ['required', 'string', 'min:3', 'max:999', 'regex:/^[A-Za-z0-9А-Яа-яЁё\s\-()]+$/u'],
            'header_code' => ['nullable','string'],
            'footer_code' => ['nullable','string'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Обязательно для заполнения. ',
            'name.string' => 'Должно быть строкой. ',
            'name.min' => 'Должно быть более 3 символов. ',
            'name.max' => 'Не должно превышать 250 символов. ',
            'name.regex' => 'Недопустимые символы в алиасе. Разрешены только латинские и русские буквы, цифры, пробелы и дефисы. ',

            'description.required' => 'Обязательно для заполнения. ',
            'description.string' => 'Должно быть строкой. ',
            'description.min' => 'Должно быть более 3 символов. ',
            'description.max' => 'Не должно превышать 999 символов. ',
            'description.regex' => 'Недопустимые символы в алиасе. Разрешены только латинские и русские буквы, цифры, пробелы и дефисы. ',

            'header_code.string' => 'Должно быть строкой. ',
            'footer_code.string' => 'Должно быть строкой. ',
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
