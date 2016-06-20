<?xml version="1.0" encoding="utf-8"?>
<soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
	<soap:Body>
		<RegisterAccount xmlns="https://ssl.selectpayment.com/PV">
			<storeId><?=$this->e($credentials['store-id'])?></storeId>
			<storeKey><?=$this->e($credentials['store-key'])?></storeKey>
			<entityId><?=$this->e($credentials['entity-id'])?></entityId>
			<account>
				<CustomerNumber><?=$this->e($account->CustomerNumber)?></CustomerNumber>
				<AccountType><?=$this->e($account->AccountType)?></AccountType>
				<NameOnAccount><?=$this->e($account->NameOnAccount)?></NameOnAccount>
				<AccountName><?=$this->e($account->AccountName)?></AccountName>
				<AccountNumber><?=$this->e($account->AccountNumber)?></AccountNumber>
				<RoutingNumber><?=$this->e($account->RoutingNumber)?></RoutingNumber>
				<BillAddress1><?=$this->e($account->BillAddress1)?></BillAddress1>
				<BillAddress2><?=$this->e($account->BillAddress2)?></BillAddress2>
				<BillCity><?=$this->e($account->BillCity)?></BillCity>
				<BillStateRegion><?=$this->e($account->BillStateRegion)?></BillStateRegion>
				<BillPostalCode><?=$this->e($account->BillPostalCode)?></BillPostalCode>
				<BillCountry><?=$this->e($account->BillCountry)?></BillCountry>
				<AccountReferenceID><?=$this->e($account->AccountReferenceID)?></AccountReferenceID>
			</account>
		</RegisterAccount>
	</soap:Body>
</soap:Envelope>