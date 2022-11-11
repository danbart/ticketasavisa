# Omnipay - TicketAsa Visa 1.0.0

**TicketAsaGT Commerce gateway for the Omnipay PHP payment processing library**

![Packagist License](https://img.shields.io/packagist/l/cloudcogsio/omnipay-firstatlanticcommerce-gateway) ![Packagist Version](https://img.shields.io/packagist/v/cloudcogsio/omnipay-firstatlanticcommerce-gateway) ![Packagist PHP Version Support (specify version)](https://img.shields.io/packagist/php-v/cloudcogsio/omnipay-firstatlanticcommerce-gateway/dev-master) ![GitHub issues](https://img.shields.io/github/issues/cloudcogsio/omnipay-firstatlanticcommerce-gateway) ![GitHub last commit](https://img.shields.io/github/last-commit/cloudcogsio/omnipay-firstatlanticcommerce-gateway)

[Omnipay](https://github.com/thephpleague/omnipay) is a framework agnostic, multi-gateway payment processing library for
PHP 5.3+. This package implements TicketAsaGT 2.4 support for Omnipay.

## Installation

Via Composer

``` bash
$ composer require danbart/omnipay-ticketasavisa
```

## Gateway Operation Defaults

This gateway driver operates in 3DS mode by default and requires a notify URL to be provided via the '**setNotifyURL**'
method.

## Usage

For general usage instructions, please see the main [Omnipay](https://github.com/thephpleague/omnipay) repository.

### 3DS Transactions (Direct Integration)

'**NotifyURL**' required. URL must be **https://**

``` php

use Omnipay\Omnipay;
try {
    $gateway = Omnipay::create('Ticketasavisa');
    $gateway
        ->setTestMode(true)  // false to use productions links  , true to use test links 
        ->setMerchantId('xxxxxxxx') 
        ->setPublicKey('xxxxxxxx') 
        ->setPrivateKey('xxxxxxxx')
        // **Required and must be https://
        ->setNotifyUrl('https://localhost/webhook.php')
        // **Required and must be https://    
        ->setReturnUrl('https://localhost/webhook.php')        
        ->setDiscount(false);
        

    $cardData = [
         'firstName' => 'Gabriel', //optional 
         'LastName' => 'Arzu', // optional
    ];

    $transactionData = [
        'card' => $cardData,
        'amount' => '1.00',   // Mandatory
        'TransactionId' => '2100001',  // mandatory, must be unique in each transaction
    ];

    $response = $gateway->purchase($transactionData)->send();

    if($response->isSuccessful())
         $response->getHostedPageURL();  // return the link with encrypted params 

         $response->redirectToHostedPage(); //Redirect automatically to payment form 

} catch (Exception $e){
    $e->getMessage();
}
```

***webhook response***
Response transaction from TicketasaGT.

```php
{
  "TransactionType": 1,
  "Approved": true,  // must be true
  "AuthorizationCode": "123456", // Authorization number from bank
  "TransactionIdentifier": "3dbff695-d7e0-4e90-8187-1e93cf13bb40", // Order Number
  "TotalAmount": 1,  //Mount
  "CurrencyCode": "320", 
  "RRN": "227603509881",
  "CardBrand": "Visa",
  "IsoResponseCode": "00", 
  "ResponseMessage": "Transaction is approved", // Message Approvement.
  "OrderIdentifier": "TICKET-ASA-3dbff695-d7e0-4e90-8187-1e93cf13bb40" // Order Identifier PREFIX +  Order Number
}
```

### Fetch status Transactions (Direct Integration)

'**fetchTransaction**' required. TransactionId

``` php

use Omnipay\Omnipay;
try {
    $gateway = Omnipay::create('Ticketasavisa');
    $gateway
        ->setTestMode(true)  // false to use productions links  , true to use test links 
        ->setMerchantId('xxxxxxxx') 
        ->setPublicKey('xxxxxxxx') 
        ->setPrivateKey('xxxxxxxx');
        

    
    $transactionData = [               
        'TransactionId' => '2100001',  // mandatory, must be unique in each transaction
    ];

    $response = $gateway->fetchTransaction($transactionData)->send();
    
    
    $response->getData();  //return the response object
    $response->isSuccessful() //  if IsoResponseCode is 00 return true 
    $response->getTransactionId() // return transactionId from object response
    $response->getTotalAmount() // return Amount from object response
    $response->getAuthorizationCode() // return authorizationCode from object response
    $response->getLastCaptureDateTime() // return date capture payment from object response
    $response->getTransactionDateTime() // return date transaction payment from object response

} catch (Exception $e){
    $e->getMessage();
}
```

***PowerTranz response***
Response fetch transaction from powerTranz.

```php
{
    "AuthorizationCode": "123456",
    "CurrencyCode": "320",
    "IsoResponseCode": "00", // successfull is 00
    "OrderSummary": {
        "CaptureCount": 1,
        "CreditCount": 0,
        "CurrencyCode": "320",
        "LastCaptureDateTime": "2022-10-31T21:38:49.663",
        "OrderIdentifier": "TICKET-ASA-4e895e54-3f5a-428c-ac30-1c0e7bd8ab86",
        "OriginalTrxnDateTime": "2022-10-31T21:38:49.663",
        "OriginalTrxnIdentifier": "4e895e54-3f5a-428c-ac30-1c0e7bd8ab86",
        "SettledAmount": 1.00,
        "TotalCaptureAmount": 1.00,
        "TotalCreditAmount": 0.00
    },
    "OtherAmount": 0.00,
    "TaxAmount": 0.00,
    "TipAmount": 0.00,
    "TotalAmount": 1.00,
    "TransactionDateTime": "2022-10-31T21:38:20.193",
    "TransactionIdentifier": "4e895e54-3f5a-428c-ac30-1c0e7bd8ab86",
    "TransactionType": 2
}
```

### Refund Payment (Direct Integration)

'**fetchTransaction**' required. TransactionId

``` php

use Omnipay\Omnipay;
try {
    $gateway = Omnipay::create('Ticketasavisa');
    $gateway
        ->setTestMode(true)  // false to use productions links  , true to use test links 
        ->setMerchantId('xxxxxxxx') 
        ->setPublicKey('xxxxxxxx') 
        ->setPrivateKey('xxxxxxxx');
    
    $transactionData = [      
         'amount' => '1.00',   // Mandatory         
        'TransactionId' => '2100001',  // mandatory, must be unique in each transaction
    ];

    $response = $gateway->refund($transactionData)->send();
    
    $response->getData();  //return the response object
    $response->isSuccessful() //  if Approved response
    $response->getExternalIdentifier() // return transactionId from object response
    $response->getTotalAmount() // return Amount from object response
    $response->getOriginalTrxnIdentifier() // return transactionId from object response
    $response->getErrorCode() // return the error code
    $response->getErrorMessage() // return  the error message
    $response->getIsoResponseCode() // return the iso Code
    $response->getResponseMessage() // return  the error general message

} catch (Exception $e){
    $e->getMessage();
}
```

***PowerTranz response***
Response refund Transaction from powerTranz.

```php
{
    "OriginalTrxnIdentifier": "a",
    "TransactionType": 5,
    "Approved": false,
    "TransactionIdentifier": "27909349-a43f-411a-9cc1-1ec6e3ab4d89",
    "TotalAmount": 1.00,
    "CurrencyCode": "220",
    "RRN": "230714276757",
    "IsoResponseCode": "96",
    "ResponseMessage": "System error",
    "ExternalIdentifier": "-81aa-42c1-960e-6b535c5f4ae3",
    "Errors": [
        {
            "Code": "451",
            "Message": "General processor error"
        }
    ]
}
```

## Support

If you are having general issues with Omnipay, we suggest posting on [Stack Overflow](http://stackoverflow.com/). Be
sure to add the [omnipay tag](http://stackoverflow.com/questions/tagged/omnipay) so it can be easily found.

If you believe you have found a bug, please report it using
the [GitHub issue tracker](https://github.com/edgarvicentesuc/PowerTranz.git/issues), or better yet, fork the library
and submit a pull request.