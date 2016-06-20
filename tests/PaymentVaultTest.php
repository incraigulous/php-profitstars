<?php

use jdavidbakr\ProfitStars\PaymentVault;
use jdavidbakr\ProfitStars\WSCustomer;
use jdavidbakr\ProfitStars\WSAccount;
use jdavidbakr\ProfitStars\WSRecurr;

class PaymentVaultTest extends PHPUnit_Framework_TestCase
{
    protected $object;

    public function setUp()
    {
        parent::setUp();
        try {
            $dotenv = new Dotenv\Dotenv(dirname(__DIR__));
            $dotenv->load();
        } catch (Exception $e) {
            exit('Could not find a .env file.');
        }

        $this->faker = Faker\Factory::create();
        $this->object = new PaymentVault([
            'store-id'=>getEnv('PROFIT_STARS_STORE_ID'),
            'store-key'=>getEnv('PROFIT_STARS_STORE_KEY'),
            'entity-id'=>getEnv('PROFIT_STARS_ENTITY_ID'),
            'location-id'=>getEnv('PROFIT_STARS_LOCATION_ID'),
        ]);
    }

    public function testTestConnection()
    {
        $this->assertTrue($this->object->TestConnection());
    }

    public function testTestCredentials()
    {
        $this->assertTrue($this->object->TestCredentials());
    }

    public function testRecurring()
    {
        $faker = \Faker\Factory::create();
        $customer_number = $this->faker->numberBetween(0, 99999);
        $account_reference_id =$this->faker->numberBetween(0, 99999);

        // 1. Register a new customer
        $cust = new WSCustomer;
        $cust->IsCompany = false;
        $cust->CustomerNumber = $customer_number;
        $cust->LastName = $faker->lastName;

        $this->assertTrue($this->object->RegisterCustomer($cust), $this->object->ResponseMessage);

        // 2. Register a new account
        $account = new WSAccount;
        $account->NameOnAccount = $faker->name;
        $account->AccountNumber = '9900000002';
        $account->RoutingNumber = '021000021';
        $account->AccountReferenceID = $account_reference_id;
        $account->CustomerNumber = $customer_number;

        $this->assertTrue($this->object->RegisterAccount($account), $this->object->ResponseMessage);

        // 3. Set up a recurring payment
        $start_date = \Carbon\Carbon::now()->addMonth(1);
        $recur = new WSRecurr;
        $recur->CustomerNumber = $customer_number;
        $recur->AccountReferenceID = $account_reference_id;
        $recur->Amount = 100;
        $recur->Frequency = 'Once_a_Month';
        $recur->PaymentDay = $start_date->day;
        $recur->StartDate = $start_date->format("Y-m-d");
        $recur->NextPaymentDate = $start_date->format("Y-m-d");
        $recur->NumPayments = 5;
        $recur->PaymentsToDate = 0;
        $recur->RecurringReferenceID = $this->faker->numberBetween(0, 99999);

        $this->assertTrue($this->object->SetupRecurringPayment($recur), $this->object->ResponseMessage);
    }

}
