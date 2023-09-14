<?php

namespace Modules\Cms\Rules\Categories;

use Illuminate\Contracts\Validation\Rule;
use Modules\Cms\Models\Category;

class UniqueUpdateCategoryRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(private int $category_id)
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return Category::where([$attribute => $value, 'type' => request()->get('type')])
            ->where('id', '!=', $this->category_id)
            ->count('id') == 0;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Category name already exists for this type.';
    }
}
