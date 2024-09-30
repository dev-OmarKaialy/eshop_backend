<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Http\Request;

class OneOf implements ValidationRule
{
    public $oneOf = [];
    public $request = null;
    public $message = "";

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(Request $request, array $oneOf, string $message = "")
    {
        $this->oneOf = $oneOf;
        $this->request = $request;
        $this->message = $message;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed $value
     * @return bool
     */

    /**
     * Run the validation rule.
     *
     * @param \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $count = 0;
        foreach ($this->oneOf as $param) {
            if ($this->request->has($param)) {
                $count++;
            }
        }
        if (!(count($this->oneOf) && ($count === 1))) {
            $fail($this->message());
        };

    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        $json_encodedList = json_encode($this->oneOf);

        return strlen(trim($this->message)) ? $this->message : "Please insert one of $json_encodedList.";
    }
}
