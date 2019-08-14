<?php

class PostmanPlugin_OrderExport_Model_ValidateCredential
{
    public function ValidatePalomaCredential($ovserver)
    {


        try {

           
            $config_onecheckout = $_POST;
            $config = $config_onecheckout['groups']['PostmanPlugin_group_id']['fields'];
			
			$ActivatePostmanPlugin = $config['PostmanPlugin_Activate']['value'];
			if($ActivatePostmanPlugin==0){return;}


            $client = new SoapClient("https://api.paloma.se/PalomaWebService.asmx?WSDL");


            $customerID = $config['PostmanPlugin_customer_ID']['value'];
            $customerHash = $config['PostmanPlugin_customer_Hash']['value'];

            $addressListID = $config['PostmanPlugin_addressList_ID']['value'];


            $results = $client->ListAddressLists(array('customerID' => $customerID,
                'customerHash' => $customerHash));

            if (is_array($results->ListAddressListsResult->AddressLists->AddressList)) {
                $listnum = count($results->ListAddressListsResult->AddressLists->AddressList);


                $IsValidCredential = FALSE;

                $newarray = array();

                for ($i = 0; $i < $listnum; $i++) {

                    $myarray[$i]['ListID'] = $results->ListAddressListsResult->AddressLists->AddressList[$i]->ListID;
                    $myarray[$i]['ListTitle'] = $results->ListAddressListsResult->AddressLists->AddressList[$i]->ListTitle;

                    if ($addressListID == $results->ListAddressListsResult->AddressLists->AddressList[$i]->ListID) {
                        $IsValidCredential = TRUE;
                    }

                    array_push($newarray, array('value' => $myarray[$i]['ListID'], 'label' => $myarray[$i]['ListTitle']));
                }

                if ($IsValidCredential == FALSE) {
                    Mage::throwException("Could not find an address list with this id.");
                }


            } else {
                Mage::throwException("Could not validate customer id and customer hash, Please check your credentials.");

            }



        } catch (Exception $e) {
            Mage::throwException($e->getMessage());
            echo 'Caught exception: ', $e->getMessage(), "\n";


        }


    }


}

?>