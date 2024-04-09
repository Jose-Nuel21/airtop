<?php
namespace App\Services\Vtpass\Data;

use App\Services\Vtpass\General;

final class EtisalatSMEData extends General
{
    public function variations(){
        return $this->serviceVariations('smile-direct');
    }

    public function buy(string $request_id, $billersCode, $variation_code, $amount, $phone)
    {
        $data = [
            "phone"=>$phone,
            "amount"=> (float)$amount,
            "variation_code"=> $variation_code,
            "billersCode"=>$billersCode,
            "serviceID"=>"smile-direct",
            "request_id"=>$request_id
        ];

        return $this->pay($data);
    }

    public function verifyEmail(string $billersCode)
    {
        $data = [
            'billersCode'=>$billersCode,
            'serviceID'=>"smile-direct",
            'api-key'=>$this->vein->apiKey(),
            'public_key'=>$this->vein->publicKey(),
        ];

        return $this->post($this->vein, "/merchant-verify/smile/email", $data);
    }
}