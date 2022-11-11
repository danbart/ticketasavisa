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
        return $this->getData()["TransactionIdentifier"];
    }

    public function getTotalAmount()
    {
        return $this->getData()["TotalAmount"];
    }

    public function getAuthorizationCode()
    {
        return $this->getData()["AuthorizationCode"];
    }

    public function getLastCaptureDateTime()
    {
        return $this->getData()["OrderSummary"]["LastCaptureDateTime"];
    }

    public function getTransactionDateTime()
    {
        return $this->getData()["TransactionDateTime"];
    }
}
