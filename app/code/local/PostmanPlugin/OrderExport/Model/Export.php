<?php

class PostmanPlugin_OrderExport_Model_Export
{

    public function exportOrder($order)
    {
        try {
		
		    $ActivatePostmanPlugin = Mage::app()->getStore()->getConfig('PostmanPlugin_OrderExport_options/PostmanPlugin_group_id/PostmanPlugin_Activate');
			if($ActivatePostmanPlugin==0){return;}
			
            $client = new SoapClient("https://api.paloma.se/PalomaWebService.asmx?WSDL");
            $timezone = new DateTimeZone('Europe/Stockholm');
            $time = date("Y-m-dTH:i:s");
            $date = new DateTime($time, $timezone);
            $subscriber = new Subscriber($order->getCustomerEmail(), str_replace($date->format('P'), '', $date->format('c')), true, $order->getBillingAddress()->getData('street'), $order->getCustomerFirstname(), $order->getCustomerLastname(), '', $order->getBillingAddress()->getCompany(), $order->getBillingAddress()->getTelephone(), $order->getBillingAddress()->getTelephone(), $order->getBillingAddress()->getFax(), $order->getBillingAddress()->getPostcode(), $order->getBillingAddress()->getCity(), $order->getBillingAddress()->getCountry());

            $customerID = Mage::app()->getStore()->getConfig('PostmanPlugin_OrderExport_options/PostmanPlugin_group_id/PostmanPlugin_customer_ID');
            $customerHash = Mage::app()->getStore()->getConfig('PostmanPlugin_OrderExport_options/PostmanPlugin_group_id/PostmanPlugin_customer_Hash');
            $addressListID = Mage::app()->getStore()->getConfig('PostmanPlugin_OrderExport_options/PostmanPlugin_group_id/PostmanPlugin_addressList_ID');

            $params = array(
                "subscribers" => array($subscriber),
                "customerID" => $customerID,
                "customerHash" => $customerHash,
                "addressListID" => $addressListID
            );

            $response = $client->__soapCall("InsertSubscribers", array($params));
            return true;


        } catch (Exception $e) {
            echo 'Caught exception: ', $e->getMessage(), "\n";
        }

    }
}


class Subscriber
{
    function Subscriber($Email, $TimeStamp, $Registered, $Address, $Firstname, $Lastname, $Title, $Company, $Phone, $MobilePhone, $Fax, $ZipCode, $City, $State)
    {
        $this->Email = $Email;
        $this->TimeStamp = $TimeStamp;
        $this->Registered = $Registered;
        $this->Address = $Address;
        $this->Firstname = $Firstname;
        $this->Lastname = $Lastname;
        $this->Title = $Title;
        $this->Company = $Company;
        $this->Phone = $Phone;
        $this->MobilePhone = $MobilePhone;
        $this->Fax = $Fax;
        $this->ZipCode = $ZipCode;
        $this->City = $City;
        $this->State = $State;
    }
}



