<?php

namespace Ysfkaya\Menu\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Ysfkaya\Menu\MenuItemCollection;
use Ysfkaya\Menu\Rules\MaxDepthRule;

class MenuRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $availableGroups = array_keys(config('menu.groups'));
        $availableLocales = array_keys(config('menu.locales'));

        $rules = [
            'group' => ['required', 'string', Rule::in($availableGroups)],
            'locale' => ['required', 'string', Rule::in($availableLocales)],
            'tree' => ['required', 'array', new MaxDepthRule($this->group)],
        ];

        $this->depthValidationRules($rules, (array) $this->input('tree', []));

        return $rules;
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        $messages = [];

        static::depthValidationMessages($messages, (array) $this->input('tree', []));

        return $messages;
    }

    public function getCollection(bool $transform = true)
    {
        $tree = $this->setGroupAndLocaleIntoTree($this->input('tree', []));

        return MenuItemCollection::make($tree, transform:$transform);
    }

    protected function setGroupAndLocaleIntoTree($items)
    {
        $newItems = [];

        foreach ($items as $item) {
            $item['group'] = $this->input('group');
            $item['locale'] = $this->input('locale');

            if (static::hasChildren($item)) {
                $item['children'] = $this->setGroupAndLocaleIntoTree($item['children']);
            }

            $newItems[] = $item;
        }

        return $newItems;
    }

    public static function depthValidationRules(&$rules, array $items, $parentKey = 'tree.', $parentIndex = null)
    {
        foreach ($items as $index => $item) {
            $key = $parentKey.$index;

            if (! is_null($parentIndex)) {
                $key = $parentKey.'.children.'.$index;
            }

            if (static::hasChildren($item)) {
                static::depthValidationRules($rules, $item['children'], $key, $index);
            }

            $titleRule = $key.'.title';
            $urlRule = $key.'.url';
            $targetRule = $key.'.target';

            $rules[$titleRule] = 'required';
            $rules[$urlRule] = 'required';
            $rules[$targetRule] = 'required|in:_self,_parent,_top,_blank';
        }
    }

    public static function depthValidationMessages(&$messages, array $items, $parentKey = 'tree.', $parentIndex = null)
    {
        foreach ($items as $index => $item) {
            $key = $parentKey.$index;

            if (! is_null($parentIndex)) {
                $key = $parentKey.'.children.'.$index;
            }

            if (static::hasChildren($item)) {
                static::depthValidationMessages($messages, $item['children'], $key, $index);
            }

            $titleMessageRequiredKey = $key.'.title.required';
            $urlMessageRequiredKey = $key.'.url.required';
            $targetMessageRequiredKey = $key.'.target.required';
            $targetMessageInKey = $key.'.target.in';

            $messages[$titleMessageRequiredKey] = trans('validation.required', ['attribute' => 'title']);
            $messages[$urlMessageRequiredKey] = trans('validation.required', ['attribute' => 'url']);
            $messages[$targetMessageRequiredKey] = trans('validation.required', ['attribute' => 'target']);
            $messages[$targetMessageInKey] = trans('validation.in', ['attribute' => 'target']);
        }
    }

    protected static function hasChildren($item): bool
    {
        return isset($item['children']) && count($item['children']) > 0;
    }
}
