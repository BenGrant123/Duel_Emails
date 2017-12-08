<?php

class Duel_Emails_Model_Cron_Observer
{

  const DEFAULT_INTERVAL = 5;

  public function sendMail() 
  {

    $senderName = Mage::getStoreConfig('trans_email/ident_general/name');
    $senderEmail = Mage::getStoreConfig('trans_email/ident_general/email');
    $interval = Mage::getStoreConfig('duelemails_options/emails/email_delay');
    $active = Mage::getStoreConfig('duelemails_options/emails/active');
    $brandId = Mage::getStoreConfig('duelemails_options/emails/brand_short_id');

    if (!$brandId) {
      return;
    }

    $handle = fopen('cron.txt', 'w');
    fwrite($handle, gettype($interval));
    fwrite($handle, $interval);

    if ((int)$interval !== 0 or $interval === '0') {
      fwrite($handle, "true");
      $interval = (int)$interval;
    } else {
      $interval = self::DEFAULT_INTERVAL;
    }
    
    //fwrite($handle, gettype($interval));
    //fwrite($handle, $interval);

    $model = Mage::getModel('duel_emails/followup');
    $followupOrders = $model->getCollection();

    // Iterate over orders
    foreach ($followupOrders as $followupOrder) {
      $deadline = strtotime($followupOrder->completed) + (86400 * $interval);
      $dateNow = Mage::getModel('core/date')->timestamp(time());
      $deadlinePassed = $dateNow - (int)$deadline;

      if ($deadlinePassed > 0) {
        $orderId = $followupOrder->order_id;
        $order = Mage::getModel('sales/order')->load($orderId);
        $items = $this->generateItemHtml($order, $brandId);

        if ($items && $active !== '0') {
          $template = Mage::getModel('core/email_template')
            ->loadDefault('duel_emails_template');

          $store = Mage::app()->getStore()->getFrontendName();
          $template->setTemplateSubject('Thank you for your recent purchase at '.$store);
          $template->setSenderEmail($senderEmail);
          $template->setSenderName($senderName);
          $toName = $order->getCustomerFirstname()." ".$order->getCustomerLastname();

          $variables = array(
            'items' => $items,
            'customer_name' => $toName,
            'order' => $order,
            'order_id' => $orderId,
            'shop' => $store
          );
          $processed = $template->getProcessedTemplate($variables);
          try {
            $toEmail = $order->getCustomerEmail();
            $template->send($toEmail, $toName, $variables);
            Mage::getSingleton('core/session')->addSuccess('Your request has been sent');
            $model->load($followupOrder->followup_id);
            //$model->delete();
          }
          catch (Exception $e) {
            Mage::getSingleton('core/session')->addError('Unable to send.');
          }
        } else {
          $model->load($followupOrder->followup_id);
          //$model->delete();
        }
      }
    }

  }
  
  protected function getfollowup_orders($order) 
  {
    $items = $order->getAllVisibleItems();
    $followupOrders = array();

    foreach ($items as $item) {
      // Get SKU / parent SKU if it's a configurable product
      $parentIds = Mage::getModel('catalog/product_type_configurable')->getParentIdsByChild($item->getProductId());
      $sku;
      if ($parentIds) {
        $sku = Mage::getModel('catalog/product')->load($parentIds[0])->getSku();
      } else {
        $sku = $item->getSku();
      }      

      // Check if item has been refunded (full quantity)
      $refunded = $item->getQtyShipped() === $item->getQtyRefunded();

      if (!$refunded) {
        array_push($followupOrders, $item);
      }
    }

    return $followupOrders;
  }

  protected function generateItemHtml($order, $brandId) 
  {
    $duelItems = $this->getfollowup_orders($order);

    if (count($duelItems) === 0) {
      return null;
    }

    $items = "<table>";
    foreach ($duelItems as $item) {
      $product = Mage::getModel('catalog/product')->load($item->getProductId());
      $sku;
      $parentIds = Mage::getModel('catalog/product_type_configurable')->getParentIdsByChild($item->getProductId());
      if ($parentIds) {
        $sku = Mage::getModel('catalog/product')->load($parentIds[0])->getSku();
      } else {
        $sku = $item->getSku();
      }

      $ctaText = $product->getData('duel_cta_text');

      if (!$ctaText) {
        $ctaText = 'Upload your picture now!';
      }

      $link = '<a href="https://duel.me/p/' 
        . $brandId . '/' . $sku . '" style="text-decoration: none; color: inherit;">' 
        . $ctaText . '</a>';
      $imageSrc = 'src="'.Mage::helper('catalog/image')->init($product, 'thumbnail')->__toString().'"';
      $productName = $item->getName();

      $adminTemplate = Mage::getStoreConfig('duelemails_options/emails/items_template');
      $addlink = str_replace("\$link", $link, $adminTemplate);
      $addproduct = str_replace("\$productName", $productName, $addlink);
      $finalTemplate = str_replace("\$imageSrc", $imageSrc, $addproduct);
      $items .= $finalTemplate;
    }

    $items .= "</table>";

    return $items;
  }


}