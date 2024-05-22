<?php

namespace App\Http\Service;

use Xendit\Configuration;
use Xendit\PaymentMethod\PaymentMethodApi;
use Xendit\QRCode;
use Xendit\VirtualAccounts;
use Xendit\EWallets;
use Xendit\Xendit;

class XenditService
{
    public function __construct()
    {
        Xendit::setApiKey(env("SECRET_KEY_XENDIT"));
        // Configuration::setXenditKey(env("SECRET_KEY_XENDIT"));
    }

    // create VA (Virtual Account)


    public function createVa(array $data): array
    {

        $response = [];
        try {
            $response = VirtualAccounts::create($data);

        } catch (\Exception $exception) {
            $response["message"] = $exception->getMessage();
        }

        return $response;
    }

    public function getVa(string $id)
    {

    }

    public function createQr($data): array
    {

        $response = [];
        try {
            $response = QRCode::create($data);
        } catch (\Exception $exception) {
            $response["message"] = $exception->getMessage();
        }
        return $response;
    }

    // create e-wallet
    public function createEWallet($data): array
    {
        $response = [];
        try {
            $response = EWallets::createEWalletCharge($data);
        } catch (\Exception $exception) {
            $response["message"] = $exception->getMessage();
        }
        return $response;
    }

    // create Credit Card
    // public function createCreditCard($data): array
    // {
    //     $response = [];
    //     try {
    //         $response = \Xendit\Cards::createCreditCardCharge($data);
    //     } catch (\Exception $exception) {
    //         $response["message"] = $exception->getMessage();
    //     }
    //     return $response;
    // }
}
