<?php
namespace App\Services\Vtpass\Data;

use App\Services\Vtpass\General;

final class DSTVSubscription extends General
{
    public function variations(){
        return $this->serviceVariations('dstv');
    }

    public function buy(string $request_id, $amount, $phone, $variation_code, $billers_code, $quantity)
    {
        $data = [
            "phone"=>$phone,
            "amount"=> (float)$amount,
            "variation_code"=> $variation_code,
            "billersCode"=>$billers_code,
            "serviceID"=>"dstv",
            "request_id"=>$request_id,
            "subscription_type"=>"change",
            "quantity"=>$quantity
        ];

        return $this->pay($data);
    }
    
    public function renew(string $request_id, $amount, $phone, $billers_code)
    {
        $data = [
            "phone"=>$phone,
            "amount"=> (float)$amount,
            "billersCode"=>$billers_code,
            "serviceID"=>"dstv",
            "request_id"=>$request_id,
            "subscription_type"=>"renew",
        ];

        return $this->pay($data);
    }

    public function verifySmartCardNumber(string $billersCode)
    {
        return $this->merchantVerify([
            "billersCode"=>$billersCode,
            "serviceID"=>"dstv"
        ]);
    }
}