<?php

namespace App\Services;

class MidtransRouter
{
    public static function resolveTargetSystem(string $orderId): ?string
    {
        foreach (config('midtrans') as $prefix => $url) {
            if ($prefix !== 'default' && str_starts_with($orderId, $prefix)) {
                return $url;
            }
        }

        return config('midtrans.default');
    }
}
