<?php
namespace App\Services\Vtpass\Data;

use App\Services\Engine;
use App\Services\Heart\VtpassVein;
use App\Services\Vtpass\General;

final class MtnAirtime extends General
{
    public function variations(){
        return $this->serviceVariations('mtn');
    }

    public function buy(string $request_id, $amount, $phone)
    {
        $data = [
            "phone"=>$phone,
            "amount"=> (float)$amount,
            "serviceID"=>"mtn",
            "request_id"=>$request_id
        ];

        return $this->pay($data);
    }
}