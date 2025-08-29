<?php

namespace App\Services;

use FedaPay\FedaPay; 
use FedaPay\Transaction; 

class FedaPayService
{
    public function __construct()
    {
        $public = config('fedapay.public_key');
        $secret = config('fedapay.secret_key');
        $environment = config('fedapay.environment', 'sandbox');

        FedaPay::setApiKey($secret);
        FedaPay::setEnvironment($environment);
    }

    public function initTransaction(array $payload)
    {
        return Transaction::create($payload);
    }

    public function retrieve($id)
    {
        return Transaction::retrieve($id);
    }
}


