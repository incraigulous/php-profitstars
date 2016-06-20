<?xml version="1.0" encoding="utf-8"?>
<soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
	<soap:Body>
		<RegisterCustomer xmlns="https://ssl.selectpayment.com/PV">
			<storeId><?=$this->e($credentials['store-id'])?></storeId>
			<storeKey><?=$this->e($credentials['store-key'])?></storeKey>
			<entityId><?=$this->e($credentials['entity-id'])?></entityId>
			<customer>
				<entityId><?=$this->e($credentials['entity-id'])?></entityId>
				<IsCompany><?=$this->e($customer->IsCompany?'1':'0')?></IsCompany>
				<CustomerNumber><?=$this->e($customer->CustomerNumber)?></CustomerNumber>
				<Field1></Field1>
				<Field2></Field2>
				<Field3></Field3>
				<FirstName><?=$this->e($customer->FirstName)?></FirstName>
				<LastName><?=$this->e($customer->LastName)?></LastName>
				<Email><?=$this->e($customer->Email)?></Email>
				<OtherEmail></OtherEmail>
				<Address1><?=$this->e($customer->Address1)?></Address1>
				<Address2><?=$this->e($customer->Address2)?></Address2>
				<City><?=$this->e($customer->City)?></City>
				<StateRegion><?=$this->e($customer->StateRegion)?></StateRegion>
				<PostalCode><?=$this->e($customer->PostalCode)?></PostalCode>
				<Country><?=$this->e($customer->Country)?></Country>
				<EveningPhone><?=$this->e($customer->EveningPhone)?></EveningPhone>
				<EveningExt><?=$this->e($customer->EveningExt)?></EveningExt>
				<DaytimePhone><?=$this->e($customer->DaytimePhone)?></DaytimePhone>
				<DaytimeExt></DaytimeExt>
				<Fax></Fax>
				<SSN><?=$this->e($customer->SSN)?></SSN>
				<DLState><?=$this->e($customer->DLState)?></DLState>
				<DLNumber><?=$this->e($customer->DLNumber)?></DLNumber>
			</customer>
		</RegisterCustomer>
	</soap:Body>
</soap:Envelope>
