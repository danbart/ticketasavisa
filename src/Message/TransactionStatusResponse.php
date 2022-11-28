<?php

namespace Omnipay\Ticketasavisa\Message;

class TransactionStatusResponse extends AbstractResponse
{

    public function isSuccessful()
    {
        if (!empty($this->getData()["IsoResponseCode"] == "00")) {
            return true;
        }

        return false;
    }

    public function isPaid()
    {
        return $this->isSuccessful();
    }

    public function getTransactionId()
    {
        return $this->getData()["reconciliationId"];
    }

    public function getTotalAmount()
    {
        return $this->getData()["orderInformation"]["amountDetails"]["totalAmount"];
    }

    public function getAuthorizationCode()
    {
        return $this->getData()["applicationInformation"]["reasonCode"];
    }

    public function getTransactionDateTime()
    {
        return $this->getData()["submitTimeUTC"];
    }

    public function getMessage()
    {
        return $this->getData()["applicationInformation"]["applications"][0]["rMessage"];
    }
}
