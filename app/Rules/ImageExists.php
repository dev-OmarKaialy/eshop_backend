<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\File;

class ImageExists implements ValidationRule
{
    public $image;

    public function __construct(string $image)
    {
        $this->image = $image;
    }

    /**
     * Run the validation rule.
     *
     * @param \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $path = storage_path('images/temp/') . $this->image;
        if (!File::exists($path))
            $fail("image not exist");
    }
}
