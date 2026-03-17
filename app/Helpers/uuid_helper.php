<?php

use Ramsey\Uuid\Uuid;

if (!function_exists('uuid')) {
    function uuid()
    {
        // Versi 3.9 tetap menggunakan metode yang sama untuk v4
        return Uuid::uuid4()->toString();
    }
}