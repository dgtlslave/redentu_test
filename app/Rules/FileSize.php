<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class FileSize implements Rule
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
        $max = preg_replace('~\D~', '', ini_get('upload_max_filesize'));
        $max = (int)$max;
        $max = $max * 1024;
        if($value > $max) {
            return $value;
        }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Your file size bigger than max uploading file size.';
    }
}
