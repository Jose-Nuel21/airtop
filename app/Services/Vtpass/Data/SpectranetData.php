<?php
namespace App\Services\Vtpass\Data;

use App\Services\Vtpass\General;

final class SpectranetData extends General
{
    public function variations(){
        return $this->serviceVariations('spectranet');
    }

    public function buy(string $request_id, $quantity, $billersCode, $variation_code, $amount, $phone)
    {
        $data = [
            "phone"=>$phone,
            "amount"=> (float)$amount,
            "variation_code"=> $variation_code,
            "billersCode"=>$billersCode,
            "serviceID"=>"spectranet",
            "request_id"=>$request_id,
            "quantity"=>(int)$quantity
        ];

        return $this->pay($data);
    }

    public function verifyEmail(string $billersCode)
    {
        $data = [
            'billersCode'=>$billersCode,
            'serviceID'=>"smile-direct",
            'api-key'=>$this->vein->apiKey(),
            'secret_key'=>$this->vein->secretKey(),
        ];

        return $this->post($this->vein, "/merchant-verify/smile/email", $data);
    }
}