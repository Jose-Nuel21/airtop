<?php
namespace App\Services\Vtpass\Data;

use App\Services\Engine;
use App\Services\Heart\VtpassVein;
use App\Services\Vtpass\General;

final class InternationalAirtime extends General
{
    public function variations(){
        return $this->serviceVariations('mtn');
    }

    public function buy(string $request_id, $amount, $phone, $operator_id, $country_code, $product_type_id, $email)
    {
        $data = [
            "phone"=>$phone,
            "amount"=> (float)$amount,
            "serviceID"=>"mtn",
            "request_id"=>$request_id,
            "operator_id"=>$operator_id,
            "country_code"=>$country_code,
            "product_type_id"=>$product_type_id,
            "email"=>$email
        ];

        return $this->pay($data);
    }

    public function airtimeCountries()
    {
        $data = [
            'api-key'=>$this->vein->apiKey(),
            'public_key'=>$this->vein->publicKey(),
        ];

        return $this->get($this->vein, "/get-international-airtime-countries", $data);
    }
    
    public function airtimeProductTypes(string $country_code)
    {
        $data = [
            'api-key'=>$this->vein->apiKey(),
            'public_key'=>$this->vein->publicKey(),
            'code'=>$country_code
        ];

        return $this->get($this->vein, "/get-international-airtime-product-types", $data);
    }
    
    public function airtimeOperators(string $country_code, string $product_type_id)
    {
        $data = [
            'api-key'=>$this->vein->apiKey(),
            'public_key'=>$this->vein->publicKey(),
            'code'=>$country_code,
            'product_type_id'=>$product_type_id
        ];

        return $this->get($this->vein, "/get-international-airtime-operators", $data);
    }
    
    public function variationCodes(string $country_code, string $product_type_id, string $operator_id)
    {
        $data = [
            'api-key'=>$this->vein->apiKey(),
            'public_key'=>$this->vein->publicKey(),
            'code'=>$country_code,
            'product_type_id'=>$product_type_id,
            'operator_id'=>$operator_id
        ];

        return $this->get($this->vein, "/service-variations", $data);
    }
}