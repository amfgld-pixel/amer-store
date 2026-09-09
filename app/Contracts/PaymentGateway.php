<?php
namespace App\Contracts;use App\Models\Order;use App\Models\PaymentTransaction;
interface PaymentGateway{public function initiate(Order $order,PaymentTransaction $transaction):array;public function verify(array $payload,PaymentTransaction $transaction):bool;}
