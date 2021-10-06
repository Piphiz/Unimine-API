<?php

namespace App\Services;

class HashValidator
{
    public static function validate($hash): bool
    {
        if (strlen($hash) != 6) {
            return false;
        }

        return ctype_alnum($hash);
    }
}
