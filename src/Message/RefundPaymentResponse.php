<?php

namespace Omnipay\Ticketasavisa\Message;

class RefundPaymentResponse extends AbstractResponse
{

    public function isSuccessful()
    {
        if (!empty($this->getData()["IsoResponseCode"] == "00")) {
            return true;
        }

        return false;
    }

    public function getApproved()
    {
        return $this->getData()["Approved"];
    }

    public function getTotalAmount()
    {
        return $this->getData()["TotalAmount"];
    }

    public function getRRN()
    {
        return $this->getData()["RRN"];
    }

    public function getExternalIdentifier()
    {
        return $this->getData()["ExternalIdentifier"];
    }

    public function getOrderIdentifier()
    {
        return $this->getData()["OrderIdentifier"];
    }

    public function getOriginalTrxnIdentifier()
    {
        return $this->getData()["OriginalTrxnIdentifier"];
    }

    public function getErrorCode()
    {
        return $this->getData()["Errors"][0]["Code"];
    }

    public function getErrorMessage()
    {
        return $this->getData()["Errors"][0]["Message"];
    }

    public function getIsoResponseCode()
    {
        return $this->getData()["IsoResponseCode"];
    }

    public function getResponseMessage()
    {
        return $this->getData()["ResponseMessage"];
    }
}
