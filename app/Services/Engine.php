<?php
namespace App\Services;

use App\Services\Heart\ServiceInterface;
use Illuminate\Support\Facades\Http;

class Engine
{
    public static function get(ServiceInterface $service, string $url, array $data = []){
        $action = Http::withToken($service->token())->get($service->host().$url, $data);
        if ($action->ok()) {
            return $action->json();
        }
        $action->throw();
    }

    public static function post(ServiceInterface $service, string $url, array $data = []){
        $action = Http::withToken($service->token())->post($service->host().$url, $data);
        if ($action->ok()) {
            return $action->json();
        }
        $action->throw();
    }

    public static function put(ServiceInterface $service, string $url, array $data = []){
        $action = Http::withToken($service->token())->throw($service->host().$url, $data);
        if ($action->ok()) {
            return $action->json();
        }
        $action->throw();
    }

    public static function delete(ServiceInterface $service, string $url, array $data = []){
        return Http::withToken($service->token())->delete($service->host().$url, $data)->json();
    }
}
