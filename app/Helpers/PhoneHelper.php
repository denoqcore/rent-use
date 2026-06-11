<?php

if (! function_exists('formatPhone')) {

    function formatPhone(string $phone): string
    {
        $digits = preg_replace('/\D/', '', $phone);

        if (strlen($digits) < 4) {
            return $phone;
        }

        $country = substr($digits, 0, 3);
        $rest    = substr($digits, 3);
        $chunks  = str_split($rest, 3);

        return '+' . $country . '-' . implode('-', $chunks);
    }
}
