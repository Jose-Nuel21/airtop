<?php
namespace App\Services\Vtpass\Data;

use App\Services\Engine;
use App\Services\Heart\VtpassVein;
use App\Services\Vtpass\General;

final class EtisalatAirtime extends General
{
    public function variations(){
        return $this->serviceVariations('etisalat');
    }

    public function buy(string $request_id, $amount, $phone)
    {
        $data = [
            "phone"=>$phone,
            "amount"=> (float)$amount,
            "serviceID"=>"etisalat",
            "request_id"=>$request_id
        ];

        return $this->pay($data);
    }
}