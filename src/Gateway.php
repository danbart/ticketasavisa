<?php

namespace Omnipay\Ticketasa;


use Symfony\Component\HttpFoundation\Request as HttpRequest;
use Omnipay\Common\AbstractGateway;
use Omnipay\Common\Http\ClientInterface;
use Omnipay\Ticketasa\Support\ParametersInterface;

class Gateway extends AbstractGateway implements ParametersInterface
{

    public function __construct(ClientInterface $httpClient = null, HttpRequest $httpRequest = null)
    {
        parent::__construct(null, $httpRequest);
    }

    public function getName()
    {
        return Constants::DRIVER_NAME;
    }

    /**
     * @param array $options
     * @return \Omnipay\Common\Message\AbstractRequest
     */
    public function purchase(array $options = []): \Omnipay\Common\Message\AbstractRequest
    {

        return $this->createRequest("\Omnipay\Ticketasa\Message\Purchase3DS", $options);
    }

    public function setNotifyUrl($url)
    {
        //$this->setReturnUrl($url);
        return $this->setParameter(Constants::CONFIG_KEY_MERCHANT_RESPONSE_URL, $url);
    }

    public function getNotifyUrl()
    {
        return $this->getParameter(Constants::CONFIG_KEY_MERCHANT_RESPONSE_URL);
    }

    public function payment(array $options = []): \Omnipay\Common\Message\AbstractRequest
    {
        return $this->createRequest("\Omnipay\TicketAsaGt\Message\Payment3DS", $options);
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

    public function setDiscount($value)
    {
        return $this->setParameter(Constants::USE_DISCOUNT_FORM, $value);
    }


    public function setOrderNumberPrefix($value)
    {
        return $this->setParameter(Constants::GATEWAY_ORDER_IDENTIFIER_PREFIX, $value);
    }

    public function getOrderNumberPrefix()
    {
        return $this->getParameter(Constants::GATEWAY_ORDER_IDENTIFIER_PREFIX);
    }

    public function setOrderNumberAutoGen($value)
    {
        return $this->setParameter(Constants::GATEWAY_ORDER_IDENTIFIER_AUTOGEN, $value);
    }

    public function getOrderNumberAutoGen()
    {
        return $this->getParameter(Constants::GATEWAY_ORDER_IDENTIFIER_AUTOGEN);
    }

}