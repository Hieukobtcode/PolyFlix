<?php

namespace App\Helpers;

use Hashids\Hashids;

class IdFormatter
{
    protected static function hashids(): Hashids
    {
        return new Hashids(config('hashids.salt'), 20); 
    }

    public static function uuidify(int $id): string
    {
        $encoded = self::hashids()->encode($id);
        return self::toFakeUuidFormat($encoded);
    }

    public static function deuuidify(string $fakeUuid): ?int
    {
        $encoded = str_replace('-', '', $fakeUuid);
        $decoded = self::hashids()->decode($encoded);
        return $decoded[0] ?? null;
    }

    protected static function toFakeUuidFormat(string $str): string
    {
        $clean = str_replace('-', '', $str);

        return substr($clean, 0, 8) . '-' .
            substr($clean, 8, 4) . '-' .
            substr($clean, 12, 4) . '-' .
            substr($clean, 16, 4); 
    }
}
