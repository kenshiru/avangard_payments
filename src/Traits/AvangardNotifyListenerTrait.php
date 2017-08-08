<?php namespace Avangard\Traits;


trait AvangardNotifyListenerTrait {

    public function calculateAvSignature($orderNumber, $amount) {
        $firstSignaturePie = md5($this->_avSign);
        $secondSignaturePie = md5(sprintf('%s%s%s', $this->_shopId, $orderNumber, $amount));
        $signature = strtoupper(
            md5(
                strtoupper(
                    sprintf('%s%s', $firstSignaturePie, $secondSignaturePie)
                )
            )
        );
        return $signature;
    }

    public function checkAvSignature($orderNumber, $amount, $givenSignature) {
        $realSignature = $this->calculateAvSignature($orderNumber, $amount);
        return $givenSignature === $realSignature;
    }

}


