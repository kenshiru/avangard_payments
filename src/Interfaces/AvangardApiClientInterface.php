<?php namespace Avangard\Interfaces;

interface AvangardApiClientInterface extends AvangardBaseInterface, AvangardApiClientConstantsInterface
{


    /**
     * Регистрация нового заказа и получение ticket
     * ticket используется для формирования ссылки
     * вида: https://www.avangard.ru/iacq/pay?ticket=123456...
     * Переходя по ссылке клиент получает форму оплаты картой
     * @param $orderOptions - параметры заказа (смотри документашку)
     * @return mixed -
     */
    public function regOrder($orderOptions);

    /**
     * Получить информацию о заказе
     * @param $ticket - получаем и сохраняем при регистрации
     * @param int $version - версия ответа (по умолчанию 2)
     * @return bool|SimpleXMLElement - false если что то не так | SimpleXMLElement instance если есть ответ
     */
    public function getOrderInfo($ticket, $version = 2);

    /**
     * Отмена заказа в системе Интернет-эквайринга
     * @param $ticket - получаем и сохраняем при регистрации
     * @param $amount - сумма заказа (не обязательно)
     * @return bool - false в случае ошибки
     * @return SimpleXMLElement - ответ Avangard в формате SimpleXMLElement
     */
    public function reverseOrder($ticket, $amount = false);

    /**
     * Сервис предназначен для получения полного списка операций по номеру заказа,
     * передаваемого из системы Интернет-магазина.
     * @param $orderNumber - Номер заказа в системе Интернет-магазина
     * @param int $responseVersion - Версия формата ответа, поддерживаются значения 1 и 2
     * @return bool - false в случае ошибки
     * @return SimpleXMLElement - ответ Avangard в формате SimpleXMLElement
     */
    public function getOpersList($orderNumber, $responseVersion = 2);

    /**
     * Сервис предназначен для получения полного списка операций за конкретную дату.
     * @param DateTime $date - дата по которой фильтруем
     * @return bool - false в случае ошибки
     * @return SimpleXMLElement - ответ Avangard в формате SimpleXMLElement
     */
    public function getOpersByDate(\DateTime $date);

    /**
     * Сервис предназначен для отмены попытки оплаты. Если по каким-либо причинам клиент
     * не хочет, что бы пользователь смог провести оплату, то он должен вызвать этот сервис.
     * @param $ticket - получаем и сохраняем при регистрации
     * @return bool|SimpleXMLElement
     */
    public function canselOrder($ticket);
}