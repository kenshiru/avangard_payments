<?php namespace Avangard;

use Avangard\Exceptions\AvangardException;
use Avangard\Interfaces\AvangardBaseInterface;
use \GuzzleHttp\Client;
use \GuzzleHttp\Exception\RequestException;
use League\Flysystem\Exception;


abstract class AvangardBase implements AvangardBaseInterface
{
    /**
     * @var bool if not setted
     * @var int - идентификатор магазина
     */
    protected $_shopId = false;

    /**
     * @var bool if not setted
     * @var string - секретный ключ магазина
     */
    protected $_shopSign = false;

    /**
     * @var bool if not setted
     * @var string - секретный ключ магазина
     */
    protected $_shopPwd = false;

    /**
     * @var bool if not setted
     * @var string - секретный ключ магазина
     */
    protected $_avSign = false;

    /**
     * AvangardAbstract constructor.
     * @param $shopId
     * @param $shopSign
     * @param $shopPwd
     * @param $avSign
     */
    public function __construct($shopId = false, $shopSign = false, $shopPwd = false, $avSign = false)
    {
        $this->_shopId = $shopId;
        $this->_shopSign = $shopSign;
        $this->_shopPwd = $shopPwd;
        $this->_avSign = $avSign;
        $this->_guzzleClient = new \GuzzleHttp\Client(array(
            'base_url' => 'https://www.avangard.ru/iacq/h2h/'
        ));
    }

    /**
     * @param $actionName - new_order, get_order_info и так далее
     * @param stdClass $options - обычный объект
     * @param bool $mixinBaseOptions - следует ли примешивать shop_id, shop_passwd ?
     * @return string - параметры запроса в xml форматы
     */
    protected function _buildXML($actionName, \stdClass $options, $mixinBaseOptions = true)
    {
        $parentNode = sprintf("<?xml version=\"1.0\" encoding=\"UTF-8\"?><%s/>", strtoupper($actionName));
        $xmlOrder = new \SimpleXMLElement($parentNode);

        if ($mixinBaseOptions) {
            $options = $this->_mixinBaseOptions($options);
        }

        foreach ($options as $optionName => $optionValue) {
            $upperOptionName = strtoupper($optionName);
            $xmlOrder->addChild($upperOptionName, $optionValue);
        }
        return $xmlOrder->asXML();
    }

    /**
     * Примешиваем к объекту параметры shop_id, shop_passwd
     * @param stdClass $options - объект в который примешиваем
     * @return stdClass - тот же options, но с параметрами shop_id, shop_passwd
     */
    protected function _mixinBaseOptions(\stdClass $options)
    {
        $options->shop_id = $this->_shopId;
        $options->shop_passwd = $this->_shopPwd;
        return $options;
    }

    protected function _checkAndParse($response)
    {
        $response = simplexml_load_string($response);
        if ( (int) $response->response_code == 0 ) {
            return $this->_xmlToSimpleObject($response);
        } else {
            throw new Exception( $response->response_message );
        }
    }

    protected function _xmlToSimpleObject ( $xmlObj, $output = array () )
    {
        foreach ( (array) $xmlObj as $index => $node ) {
            $output[$index] = (is_object($node)) ? $this->_xmlToSimpleObject($node): $node;
        }
        return (object) $output;
    }

    protected function request($method, $options, $uriMethod = false)
    {
        try {
            $requestOptions = $this->_buildXML($method, (object)$options);
            if ( ! $uriMethod ) {
                $uriMethod = $method;
            }
                $response = $this->_guzzleClient->post($uriMethod, [
                    'query' => [
                        'xml' => $requestOptions
                    ]
                ]);

            $responseBody = $response->getBody();
            $response = $responseBody;
            return $this->_checkAndParse($response);
        } catch (RequestException $requestException) {
            throw new AvangardException($requestException->getMessage());
        }
    }

    /**
     * Отправляем запрос и возвращаем ответ
     * @param $url - куда отправляем
     * @param $stringXml - отправляем параметром POST[xml]
     * @return mixed - xml response
     */
    protected function _sendHttpRequest($url, $stringXml)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, sprintf("xml=%s", $stringXml));
        $response = curl_exec($ch);
        if ($response) {
            return simplexml_load_string($response);
        } else {
            return false;
        }
    }
}
