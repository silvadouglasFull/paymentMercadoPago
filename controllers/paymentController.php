<?php
require('../config/config.php');
require('../lib/vendor/autoload.php');
// getting all fields from the json sent.
/*
SAND_TOKEN global variable defined in config file in /config/config.php directory
*/
$data = json_decode(file_get_contents('php://input'), true);
//MercadoPago\SDK from php composer.phar require "mercadopago/dx-php"
MercadoPago\SDK::setAccessToken(SAND_TOKEN);

$payment = new MercadoPago\Payment();
$payment->token = $data['token'];
$payment->description = $data['description'];
$payment->issuer_id = (int)$data['issuer_id'];
$payment->installments = (int)$data['installments'];
$payment->payment_method_id = $data['payment_method_id'];
$payment->transaction_amount = (float)$data['transaction_amount'];
$payer = new MercadoPago\Payer();
$payer->email = $data['payer']['email'];
$payer->identification = array(
    "type" => $data['payer']['type'],
    "number" => $data['payer']['number']
);
$payment->payer = $payer;
//save method sends the payment information to the paid market and returns an object with the payment status
$payment->save();
$response = array(
    'status' => $payment->status,
    'status_detail' => $payment->status_detail,
    'id' => $payment->id
);
// $payment->id stores response.id in the database
echo json_encode($response);
