<?php

namespace App\Services\Vtpass;

use App\Services\Engine;
use App\Services\Heart\VtpassVein;

class General extends Engine
{
    protected $vein, $serviceEngine;

    public function __construct()
    {
        $this->vein = new VtpassVein;
    }

    public function serviceVariations(string $serviceID)
    {
        $data = [
            'api-key'=>$this->vein->apiKey(),
            'public_key'=>$this->vein->publicKey(),
            'serviceID'=>$serviceID
        ];

        try {
            return $this->get($this->vein, '/service-variations', $data);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function pay(array $data)
    {
        $data['api_key'] = $this->vein->apiKey();
        $data['secret_key'] = $this->vein->secretKey();

        try {
            return $this->post($this->vein, '/pay', $data);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function merchantVerify(array $data)
    {
        $data['api_key'] = $this->vein->apiKey();
        $data['secret_key'] = $this->vein->secretKey();

        try {
            return $this->post($this->vein, '/merchant-verify', $data);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function queryStatus(string $request_id)
    {
        $data = [
            'api-key'=>$this->vein->apiKey(),
            'public_key'=>$this->vein->publicKey(),
            'request_id'=>$request_id
        ];

        try {
            return $this->get($this->vein, '/requery', $data);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function requestID(): string
    {
        return date('YYYYMMDDHHII').'-'.random_bytes(5);
    }
}