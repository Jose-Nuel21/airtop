<?php
namespace App\Services\Vtpass\Data;

use App\Services\Engine;
use App\Services\Heart\VtpassVein;
use App\Services\Vtpass\General;

final class GloAirtime extends General
{
    public function variations(){
        return $this->serviceVariations('glo');
    }

    public function buy(string $request_id, $amount, $phone)
    {
        $data = [
            "phone"=>$phone,
            "amount"=> (float)$amount,
            "serviceID"=>"glo",
            "request_id"=>$request_id
        ];

        return $this->pay($data);
    }
}