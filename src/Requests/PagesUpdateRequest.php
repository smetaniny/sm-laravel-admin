<?php

namespace Smetaniny\SmLaravelAdmin\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\Contracts\Validation\Validator;


class PagesUpdateRequest extends PagesBaseRequest
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
            'title' => [
                'nullable',
                'string',
                Rule::unique((new Page)->getTable())->ignore($this->id)
            ],
            'alias' => [
                ['nullable', 'string', 'regex:/^[A-Za-z0-9А-Яа-яЁё\s\-()\/]+$/u'],
                Rule::unique((new Page)->getTable())->ignore($this->id)
            ],
            'canonical_url' => ['nullable', 'string', 'regex:/^[A-Za-z0-9А-Яа-яЁё\s\-()\/]+$/u'],
            'description' => ['nullable', 'string'],
            'menutitle' => ['nullable', 'string'],
            'menuindex' => ['nullable', 'integer', 'min:1'],
            'content_js' => ['sometimes', 'nullable', 'array'],

            'robots' => [
                'string',
                'regex:/^(index|follow|noindex|nofollow)(,(index|follow|noindex|nofollow))*$/',
            ],

            'sitemap_priority' => ['nullable', 'numeric', 'between:0,1'],
            'sitemap_frequency' => ['string', 'in:always,hourly,daily,weekly,monthly,yearly,never'],
            'meta_title' => ['nullable', 'string'],
            'meta_description' => ['nullable', 'string'],

            'og_title' => ['nullable', 'string'],
            'og_description' => ['nullable', 'string'],
            'twitter_title' => ['nullable', 'string'],
            'twitter_description' => ['nullable', 'string'],
            'twitter_image' => ['nullable', 'string'],

            'is_published' => ['boolean'],
            'is_visible_url' => ['boolean'],
            'is_open' => ['boolean'],

            'published_at' => ['nullable', 'date'],
            'unpublished_at' => ['nullable', 'date'],
            'css' => ['nullable', 'string'],
            'js' => ['nullable', 'string'],

            'parent_id' => ['nullable', 'integer', 'min:1'],
            'template_id' => ['required', 'integer', 'min:1'],
            'category_id' => ['required', 'integer', 'min:1'],
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
