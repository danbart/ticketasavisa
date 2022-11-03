<?php

namespace Omnipay\Ticketasa\Message;

use Omnipay\Ticketasa\Exception\InvalidResponseData;
use Omnipay\Ticketasa\Support\Cryptor;
use Omnipay\Ticketasa\Support\ParametersInterface;
use Omnipay\Ticketasa\Constants;

abstract class AbstractRequest extends \Omnipay\Common\Message\AbstractRequest
    implements ParametersInterface
{

    protected $data = [];

    protected $commonHeaders = [
        'Accept' => 'application/json',
        'Content-Type' => 'application/json'
    ];

    protected $PWTServices = [
        "Purchase" => [
            "request" => "HostedPage",
            "response" => "HostedPageResponse"
        ],
        "TransactionStatus" => [
            "request" => "Transactions",
            "response" => "TransactionStatusResponse"
        ],
        "Refund" => [
            "request" => "refund",
            "response" => "RefundPaymentResponse"
        ],
    ];

    public function sendData($data)
    {

        //  print_r($data);
        // print_r($this->getEndpoint() . $this->getMessageClassName());

        switch ($this->getMessageClassName()) {

            case "HostedPage":
                return $this->response = new HostedPageResponse($this, $data);

            case "TransactionStatus" :

                $this->addCommonHeaders($data);
                $requestBody = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

                $uri = $this->getEndpoint() . $this->PWTServices[$this->getMessageClassName()]["request"] . "/" . "4e895e54-3f5a-428c-ac30-1c0e7bd8ab86";

                //print_r($uri);

               // print_r($this->commonHeaders);

                $httpResponse = $this->httpClient->request(
                    "GET",
                    $uri,
                    $this->commonHeaders,
                    null
                );


                return $this->response = new TransactionStatusResponse($this, $httpResponse);



            default:
                throw new InvalidResponseData($this->getMessageClassName());
        }
    }

    protected function addCommonHeaders($data): AbstractRequest
    {
        $this->commonHeaders['PowerTranz-PowerTranzId'] = $data["PWTId"];
        $this->commonHeaders['PowerTranz-PowerTranzPassword'] = $data["PWTpwd"];
        return $this;
    }


    protected function getEndpoint()
    {
        return ($this->getTestMode()) ? Constants::API_STAGING : Constants::API_PRODUCTION;
    }

    public function getMessageClassName()
    {
        $className = explode("\\", get_called_class());
        return array_pop($className);
    }

    public function setPWTId($PWTID)
    {
        return $this->setParameter(Constants::CONFIG_KEY_PWTID, $PWTID);
    }

    public function getPWTId()
    {
        return $this->getParameter(Constants::CONFIG_KEY_PWTID);
    }

    public function setPWTPwd($PWD)
    {
        return $this->setParameter(Constants::CONFIG_KEY_PWTPWD, $PWD);
    }

    public function getPWTPwd()
    {
        return $this->getParameter(Constants::CONFIG_KEY_PWTPWD);
    }


    protected function createQueryParamProtect($data)
    {
        return json_encode($data);
    }


    public function getTransactionId()
    {
        //  print_r($this->getParameter(Constants::CONFIG_TRANSACTION_IDENTIFIER));
        return $this->getParameter(Constants::CONFIG_TRANSACTION_IDENTIFIER);
    }

    public function setTransactionId($value)
    {
        //  print_r($value);
        return $this->setParameter(Constants::CONFIG_TRANSACTION_IDENTIFIER, $value);
    }

    public function setOrderIdentifier($value)
    {
        return $this->setParameter(Constants::GATEWAY_ORDER_IDENTIFIER, $value);
    }

    public function getOrderIdentifier()
    {
        return $this->getParameter(Constants::GATEWAY_ORDER_IDENTIFIER);
    }

    public function setOrderNumberPrefix($value)
    {
        return $this->setParameter(Constants::GATEWAY_ORDER_IDENTIFIER_PREFIX, $value);
    }

    public function getOrderNumberPrefix()
    {
        return $this->getParameter(Constants::GATEWAY_ORDER_IDENTIFIER_PREFIX);
    }


//    public function setOrderNumberAutoGen($value)
//    {
//        return $this->setParameter(Constants::GATEWAY_ORDER_IDENTIFIER_AUTOGEN, $value);
//    }
//
//    public function getOrderNumberAutoGen()
//    {
//        return $this->getParameter(Constants::GATEWAY_ORDER_IDENTIFIER_AUTOGEN);
//    }


    public function setDiscount($value)
    {
        return $this->setParameter(Constants::CONFIG_APPLY_DISCOUNT, $value);
    }

    public function getDiscount()
    {
        return $this->getParameter(Constants::CONFIG_APPLY_DISCOUNT);
    }

}