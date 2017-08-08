<?php namespace Avangard\Traits;


trait AvangardApiClientTrait
{

    /**
     * Регистрация нового заказа и получение ticket
     * ticket используется для формирования ссылки
     * вида: https://www.avangard.ru/iacq/pay?ticket=123456...
     * Переходя по ссылке клиент получает форму оплаты картой
     * @param $orderOptions - параметры заказа (смотри документашку)
     * @return mixed -
     */
    public function regOrder($orderOptions)
    {
        $uriMethod = 'reg';
        return $this->request(self::METHOD_REGISTRATION, $orderOptions, $uriMethod);
    }

    /**
     * Получить информацию о заказе
     * @param $ticket - получаем и сохраняем при регистрации
     * @param int $version - версия ответа (по умолчанию 2)
     * @return bool|SimpleXMLElement - false если что то не так | SimpleXMLElement instance если есть ответ
     */
    public function getOrderInfo($ticket, $version = 2)
    {
        $options = new \stdClass();
        $options->version = $version;
        $options->ticket = $ticket;
        return $this->request(self::METHOD_GET_ORDER_INFO, $options);
    }

    /**
     * Отмена заказа в системе Интернет-эквайринга
     * @param $ticket - получаем и сохраняем при регистрации
     * @param $amount - сумма заказа (не обязательно)
     * @return bool - false в случае ошибки
     * @return SimpleXMLElement - ответ Avangard в формате SimpleXMLElement
     */
    public function reverseOrder($ticket, $amount = false)
    {
        $options = new \stdClass();
        $options->ticket = $ticket;
        if ($amount) {
            $options->amount = $amount;
        }
        return $this->request(self::METHOD_REVERSE_ORDER, $options);
    }

    /**
     * Сервис предназначен для получения полного списка операций по номеру заказа,
     * передаваемого из системы Интернет-магазина.
     * @param $orderNumber - Номер заказа в системе Интернет-магазина
     * @param int $responseVersion - Версия формата ответа, поддерживаются значения 1 и 2
     * @return bool - false в случае ошибки
     * @return SimpleXMLElement - ответ Avangard в формате SimpleXMLElement
     */
    public function getOpersList($orderNumber, $responseVersion = 2)
    {
        $options = new \stdClass();
        $options->order_number = $orderNumber;
        $options->version = $responseVersion;
        return $this->request(self::METHOD_GET_ORDER_OPERATIONS, $options);
    }

    /**
     * Сервис предназначен для получения полного списка операций за конкретную дату.
     * @param DateTime $date - дата по которой фильтруем
     * @return bool - false в случае ошибки
     * @return SimpleXMLElement - ответ Avangard в формате SimpleXMLElement
     */
    public function getOpersByDate(\DateTime $date)
    {
        $options = new \stdClass();
        $options->date = $date->format('d.m.Y');
        return $this->request(self::METHOD_GET_ORDER_OPERATIONS_BY_DATE, $options);
    }

    /**
     * Сервис предназначен для отмены попытки оплаты. Если по каким-либо причинам клиент
     * не хочет, что бы пользователь смог провести оплату, то он должен вызвать этот сервис.
     * @param $ticket - получаем и сохраняем при регистрации
     * @return bool|SimpleXMLElement
     */
    public function canselOrder($ticket) {
        $options = new \stdClass();
        $options->ticket = $ticket;
        return $this->request(self::METHOD_CANCEL_ORDER, $options);
    }
}