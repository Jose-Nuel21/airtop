<?php
namespace App\Services\Heart;

interface ServiceInterface{
    public function token():string | null;

    public function host(): string;
}