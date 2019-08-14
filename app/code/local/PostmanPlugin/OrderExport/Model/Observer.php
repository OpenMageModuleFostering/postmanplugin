<?php

class PostmanPlugin_OrderExport_Model_Observer
{


    public function exportOrder(Varien_Event_Observer $observer)
    {
        $order = $observer->getEvent()->getOrder();

        Mage::getModel('PostmanPlugin_orderexport/export')
            ->exportOrder($order);

        return true;

    }
}