<?php namespace Avangard\Interfaces;


interface AvangardNotifyListenerInterface extends AvangardBaseInterface
{
    public function calculateAvSignature($orderNumber, $amount);
    public function checkAvSignature($orderNumber, $amount, $givenSignature);
}