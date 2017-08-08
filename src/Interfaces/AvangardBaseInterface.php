<?php namespace Avangard\Interfaces;


interface AvangardBaseInterface
{
    /**
     * AvangardAbstract constructor.
     * @param $shopId
     * @param $shopSign
     * @param $shopPwd
     * @param $avSign
     */
    public function __construct($shopId = false, $shopSign = false, $shopPwd = false, $avSign = false);

}