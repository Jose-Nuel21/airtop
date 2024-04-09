<?php
namespace App\Services\Vtpass\Data;

use App\Services\Engine;
use App\Services\Heart\VtpassVein;
use App\Services\Vtpass\General;

final class GloData extends General
{
    public function variations(){
        return $this->serviceVariations('glo-data');
    }

    public function buy(string $request_id, $billersCode, $variation_code, $amount, $phone)
    {
        $data = [
            "phone"=>$phone,
            "amount"=> (float)$amount,
            "variation_code"=> $variation_code,
            "billersCode"=>$billersCode,
            "serviceID"=>"glo-data",
            "request_id"=>$request_id
        ];

        return $this->pay($data);
    }
}