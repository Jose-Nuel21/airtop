<?php
namespace App\Services\Vtpass\Data;

use App\Services\Engine;
use App\Services\Heart\VtpassVein;
use App\Services\Vtpass\General;

final class AitelAirtime extends General
{
    public function variations(){
        return $this->serviceVariations('airtel');
    }

    public function buy(string $request_id, $amount, $phone)
    {
        $data = [
            "phone"=>$phone,
            "amount"=> (float)$amount,
            "serviceID"=>"airtel",
            "request_id"=>$request_id
        ];

        return $this->pay($data);
    }
}