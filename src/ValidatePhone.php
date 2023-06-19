<?php

namespace Oolayemi\ValidatePhone;

use Illuminate\Contracts\Validation\Rule;

class ValidatePhone implements Rule
{
    public string $selectedCountry;
    public ?string $message;

    /**
     * @param string $selectedCountry
     */
    public function __construct(string $selectedCountry = "NG")
    {
        $this->selectedCountry = strtolower($selectedCountry);
    }

    public function passes($attribute, $value): bool
    {
        $isCountryAvailable = array_key_exists($this->selectedCountry, $this->availableValidations());

        if (!$isCountryAvailable) {
            $this->message =  'The :attribute validation for the selected country does not exist.';
            return false;
        }

        $this->message = null;
        return preg_match($this->availableValidations()[$this->selectedCountry], $value);
    }

    /**
     * @return array|string
     */
    public function message(): array|string
    {
        return $this->message ?? "The :attribute must be a valid ". strtoupper($this->selectedCountry) . " phone number.";
    }

    /**
     * @return array<string, string>
     */
    protected function availableValidations(): array
    {
        return [
            "ng" => '/^(\+?234|0)[789]\d{9}$/',
            "ca" => '/^(?:(?:\+?1\s*(?:[.-]\s*)?)?(?:\(\s*([2-9]1[02-9]|[2-9][02-8]1|[2-9][02-8][02-9])\s*\)|([2-9]1[02-9]|[2-9][02-8]1|[2-9][02-8][02-9]))\s*(?:[.-]\s*)?)?([2-9]1[02-9]|[2-9][02-9]1|[2-9][02-9]{2})\s*(?:[.-]\s*)?([0-9]{4})$/',
            "us" => '/^\+?1?[-.\s]?\(?([0-9]{3})\)?[-.\s]?([0-9]{3})[-.\s]?([0-9]{4})$/',
            'uk' => '/^(?:(?:\+|00)44[\s-]?\d{2}[\s-]?\d{4}[\s-]?\d{4}|\(?(?:0\d{3})\)?[\s-]?\d{3}[\s-]?\d{4}|\(?(?:0\d{2})\)?[\s-]?\d{4}[\s-]?\d{4}|\(?(?:0\d{4})\)?[\s-]?\d{4,5})$/'
        ];
    }
}