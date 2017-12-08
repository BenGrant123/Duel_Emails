<?php

class Duel_Emails_Model_Observer
{

  public function orderExport(Varien_Event_Observer $observer) 
  {

    $event = $observer->getEvent();
    $order = $event->getShipment()->getOrder();
    $orderId = $order->getId();
    $active = Mage::getStoreConfig('duelemails_options/emails/active');


    if ($this->hasDuels($order) && $active !== '0') {
      $entry = array(
        'order_id' => $orderId
      );

      $model = Mage::getModel('duel_emails/followup');
      try {
        $model->setData($entry)->save();
      } catch (Exception $e) {
        Mage::log($e->getMessage(), null, "system.log");
      }
    }
  }

  protected function hasDuels($order) 
  {
    $items = $order->getAllVisibleItems();
    
    $itemEnabledEmails = array_map(
        function ($item) {
            $parentIds = Mage::getModel('catalog/product_type_configurable')
              ->getParentIdsByChild($item->getProductId());
            if ($parentIds) {
              return (Mage::getModel('catalog/product')
                ->load($parentIds[0])
                  ->getData('duel_email_enabled')) ? true: false;
            } else {
              return $item->getProduct()->getData('duel_email_enabled') ? true : false;
            }
      
        }, $items
    );


    if (in_array(true, $itemEnabledEmails)) {
      return true;
    } else {
      return false;
    }
    
  }

}