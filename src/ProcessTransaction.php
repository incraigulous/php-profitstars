<?php

namespace jdavidbakr\ProfitStars;

class ProcessTransaction extends RequestBase {
    protected $endpoint = 'https://ws.eps.profitstars.com/PV/TransactionProcessing.asmx';
    public $ReferenceNumber;
    public $ResponseMessage;

    /**
     * Tests the connection to the web service
     */
    public function TestConnection()
    {
        $view = $this->views->render('process-transaction/test-connection', ['credentials' => $this->getCredentials()]);
        $xml = $this->Call($view);
        if(!$xml) {
            throw new \Exception($this->faultstring);
        }
        return (bool)$xml->TestConnectionResult[0];
    }

    public function TestCredentials()
    {
        $view = $this->views->render('process-transaction/test-credentials', ['credentials' => $this->getCredentials()]);
        $xml = $this->Call($view);
        if(!$xml) {
            throw new \Exception($this->faultstring);
        }
        return $xml->TestCredentialsResult[0]->returnValue[0] == 'Success';
    }

    public function AuthorizeTransaction(WSTransaction $trans)
    {
        $view = $this->views->render('process-transaction/authorize-transaction',[
            'credentials' => $this->getCredentials(),
            'trans'=>$trans,
        ]);
        $xml = $this->Call($view);
        if(!$xml) {
            $this->ResponseMessage = $this->faultstring;
            return false;
        }
        if(!$xml->AuthorizeTransactionResult[0] || (string)$xml->AuthorizeTransactionResult[0]->Success[0] != 'true') {
            if($xml->AuthorizeTransactionResult[0] && (string)$xml->AuthorizeTransactionResult[0]->ResponseMessage[0]) {
                $this->ResponseMessage = (string)$xml->AuthorizeTransactionResult[0]->ResponseMessage[0];
            } else {
                // Had an error with the call that was not captured above, so let's log it and throw a 500 error for future development
                error_log($xml->asXML());
                throw new \Exception("AuthorizeTransaction error occurred");
            }
            return false;
        }
        $this->ReferenceNumber = (string)$xml->AuthorizeTransactionResult[0]->ReferenceNumber[0];
        return true;
    }

    /**
     * If not using AuthorizeTransaction, then set ReferenceNumber before calling this
     * @param [type] $amount [description]
     */
    public function CaptureTransaction($amount)
    {
        $view = $this->views->render('process-transaction/capture-transaction',[
            'credentials' => $this->getCredentials(),
            'captureAmount'=>$amount,
            'originalReferenceNumber'=>$this->ReferenceNumber,
        ]);
        $xml = $this->Call($view);
        if(!$xml) {
            $this->ResponseMessage = $this->faultstring;
            return false;
        }
        if(!$xml->CaptureTransactionResult[0] || (string)$xml->CaptureTransactionResult[0]->Success[0] != 'true') {
            if($xml->CaptureTransactionResult[0] && (string)$xml->CaptureTransactionResult[0]->ResponseMessage[0]) {
                $this->ResponseMessage = (string)$xml->CaptureTransactionResult[0]->ResponseMessage[0];
            } else {
                // Had an error with the call that was not captured above, so let's log it and throw a 500 error for future development
                error_log($xml->asXML());
                throw new \Exception('CaptureTransaction error occurred');
            }
            return false;
        }
        // This reference number cannot be used to void/refund in the future
        $this->ReferenceNumber = (string)$xml->CaptureTransactionResult[0]->ReferenceNumber[0];
        return true;
    }

    public function VoidTransaction()
    {
        $view = $this->views->render('process-transaction/void-transaction',[
            'credentials' => $this->getCredentials(),
            'originalReferenceNumber'=>$this->ReferenceNumber,
        ]);
        $xml = $this->Call($view);
        if(!$xml) {
            $this->ResponseMessage = $this->faultstring;
            return false;
        }
        if(!$xml->VoidTransactionResult[0] || (string)$xml->VoidTransactionResult[0]->Success[0] != 'true') {
            if($xml->VoidTransactionResult[0] && (string)$xml->VoidTransactionResult[0]->ResponseMessage[0]) {
                $this->ResponseMessage = (string)$xml->VoidTransactionResult[0]->ResponseMessage[0];
            } else {
                // Had an error with the call that was not captured above, so let's log it and throw a 500 error for future development
                error_log($xml->asXML());
                throw new \Exception('CaptureTransaction error occurred');
            }
            return false;
        }
        $this->ReferenceNumber = (string)$xml->VoidTransactionResult[0]->ReferenceNumber[0];
        return true;
    }

    /**
     * Unlike Authorize.net, we can only refund the entire amount of the ACH
     */
    public function RefundTransaction()
    {
        $view = $this->views->render('process-transaction/refund-transaction',[
            'credentials' => $this->getCredentials(),
            'originalReferenceNumber'=>$this->ReferenceNumber,
        ]);
        // dd($view->render());
        $xml = $this->Call($view);
        if(!$xml) {
            $this->ResponseMessage = $this->faultstring;
            return false;
        }
        if(!$xml->RefundTransactionResult[0] || (string)$xml->RefundTransactionResult[0]->Success[0] != 'true') {
            if($xml->RefundTransactionResult[0] && (string)$xml->RefundTransactionResult[0]->ResponseMessage[0]) {
                $this->ResponseMessage = (string)$xml->RefundTransactionResult[0]->ResponseMessage[0];
            } else {
                // Had an error with the call that was not captured above, so let's log it and throw a 500 error for future development
                error_log($xml->asXML());
                throw new \Exception('CaptureTransaction error occurred');
            }
            return false;
        }
        // Response message tells when the refund will take place.
        $this->ResponseMessage = (string)$xml->RefundTransactionResult[0]->ResponseMessage[0];
        $this->ReferenceNumber = (string)$xml->RefundTransactionResult[0]->ReferenceNumber[0];
        return true;
    }

}