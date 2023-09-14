<?php

namespace Modules\Cms\Rules\Media;

use Illuminate\Contracts\Validation\Rule;

class ValidFileNameRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
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
        if (strlen($value) > 255) {
            return false;
        }

        $invalidCharacters = '|\'\\?*&<";:>+[]=/';

        if (false !== strpbrk($value, $invalidCharacters)) {
            return false;
        }

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Media name must be a valid file name.';
    }
}
