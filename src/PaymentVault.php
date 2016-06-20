<?php

namespace jdavidbakr\ProfitStars;

class PaymentVault extends RequestBase {

    protected $endpoint = 'https://ws.eps.profitstars.com/PV/PaymentVault.asmx';
    public $ReferenceNumber;
    public $ResponseMessage;

    /**
     * Tests the connection to the web service
     */
    public function TestConnection()
    {
        $view = $this->views->render('payment-vault/test-connection', ['credentials' => $this->getCredentials()]);
        $xml = $this->Call($view);
        if(!$xml) {
            throw new \Exception($this->faultstring);
        }
        return (bool)$xml->TestConnectionResult[0];
    }

    public function TestCredentials()
    {
        $view = $this->views->render('payment-vault/test-credentials', ['credentials' => $this->getCredentials()]);
        $xml = $this->Call($view);
        if(!$xml) {
            throw new \Exception($this->faultstring);
        }
        return $xml->TestCredentialsResult[0]->returnValue[0] == 'Success';
    }

    public function RegisterCustomer(WSCustomer $customer)
    {
        $view = $this->views->render('payment-vault/register-customer', [
                'credentials' => $this->getCredentials(),
                'customer'=>$customer,
            ]);
        // dd($view->render());
        $xml = $this->Call($view);
        if(!$xml) {
            $this->ResponseMessage = $this->faultstring;
            return false;
        }
        if(!$xml->RegisterCustomerResult || (string)$xml->RegisterCustomerResult->returnValue[0] != 'Success') {
            if($xml->RegisterCustomerResult && (string)$xml->RegisterCustomerResult->ResponseMessage[0]) {
                $this->ResponseMessage = (string)$xml->RegisterCustomerResult->ResponseMessage[0];
            } else {
                // Had an error with the call that was not captured above, so let's log it and throw a 500 error for future development
                error_log($xml->asXML());
                throw new \Exception("RegisterCustomer error occurred");
            }
            return false;
        }
        return true;
    }

    public function RegisterAccount(WSAccount $account)
    {
        $view = $this->views->render('payment-vault/register-account', [
                'credentials' => $this->getCredentials(),
                'account'=>$account,
            ]);
        // dd($view->render());
        $xml = $this->Call($view);
        if(!$xml) {
            $this->ResponseMessage = $this->faultstring;
            return false;
        }
        if(!$xml->RegisterAccountResult || (string)$xml->RegisterAccountResult->returnValue[0] != 'Success') {
            if($xml->RegisterAccountResult && (string)$xml->RegisterAccountResult->ResponseMessage[0]) {
                $this->ResponseMessage = (string)$xml->RegisterAccountResult->ResponseMessage[0];
            } else {
                // Had an error with the call that was not captured above, so let's log it and throw a 500 error for future development
                error_log($xml->asXML());
                throw new \Exception("RegisterAccount error occurred");
            }
            return false;
        }
        return true;
    }

    public function SetupRecurringPayment(WSRecurr $recur)
    {
        $view = $this->views->render('payment-vault/setup-recurring-payment', [
                'credentials' => $this->getCredentials(),
                'recur'=>$recur,
            ]);
        // dd($view->render());
        $xml = $this->Call($view);
        if(!$xml) {
            $this->ResponseMessage = $this->faultstring;
            return false;
        }
        if(!$xml->SetupRecurringPaymentResult || (string)$xml->SetupRecurringPaymentResult->returnValue[0] != 'Success') {
            if($xml->SetupRecurringPaymentResult && (string)$xml->SetupRecurringPaymentResult->message[0]) {
                $this->ResponseMessage = (string)$xml->SetupRecurringPaymentResult->message[0];
            } else {
                // Had an error with the call that was not captured above, so let's log it and throw a 500 error for future development
                error_log($xml->asXML());
                throw new \Exception("SetupRecurringPayment error occurred");
            }
            return false;
        }
        return true;
    }

}