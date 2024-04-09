<?php

namespace App\Services\Heart;

use App\Services\Engine;

class VtpassVein implements ServiceInterface
{
    public function token(): ?string
    {
        return  "";
    }

    public function host(): string
    {
        return config('services.vein.vtpass.host');
    }

    public function apiKey(): string
    {
        return config('services.vein.vtpass.api_key');
    }
    
    public function secretKey(): string
    {
        return config('services.vein.vtpass.secret_key');
    }
    
    public function publicKey(): string
    {
        return config('services.vein.vtpass.public_key');
    }
}