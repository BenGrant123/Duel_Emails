<?php

class Duel_Emails_DuelController extends Mage_Core_Controller_Front_Action
{

  public function preDispatch()
  {
      parent::preDispatch();
      if ($this->getRequest()->getActionName() == Mage::getStoreConfig('duelemails_options/galleries/hash')) {
          $this->_forward('feed');
      }
      
      return $this;
  }

  public function hasAction($action)
  {
      return true;
  }
  
  public function feedAction()
  {
    $reqEtag = (string) Mage::app()->getRequest()->getHeader('If-None-Match');

    $collection = Mage::getModel('catalog/product')->getCollection()
    ->addAttributeToSelect(array('entity_id'), 'inner')
    ->addFieldToFilter('duel_feed_enabled', true);
    $currency = Mage::app()->getStore()->getCurrentCurrencyCode();
    $storeId = Mage::app()->getStore()->getStoreId();
    $mediaPath = Mage::app()->getStore($storeId)->getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA);
    $productRepo = Mage::getModel('catalog/product');
    $productsArray = array();

    foreach ($collection as $value) {
      $product = $productRepo->load($value->getId());
      $stockItem = $product->getStockItem();
      $thumbnail = $product->getData('thumbnail');
      $srcImg = $thumbnail == 'no_selection' ? null : $mediaPath . 'catalog/product' . $thumbnail;
      $row = array(
        'sku' => $product->getSku(),
        'name' => $product->getName(),
        'description' => strip_tags($product->getShortDescription()),
        'url' => $product->getProductUrl(),
        'srcImg' => $srcImg,
        'price' => number_format($product->getPrice(), 2),
        'currency' => $currency
        );
      if (!$stockItem->getIsInStock()) {
        $row['noStock'] = true;
      }
      array_push($productsArray, $row);

    }

    $productsObj = (object)array();
    $productsObj->items = $productsArray;
    $json = json_encode($productsObj);
    $checksum = (string) md5($json);
    $etag = 'W/' . '"' . $checksum . '"';
    if ($etag == $reqEtag) {
      $this->getResponse()
      ->clearHeaders()
      ->setHeader('ETag', $etag)
      ->setHeader('HTTP/1.0', 304, true);
    } else {
      $this->getResponse()
      ->clearHeaders()
      ->setHeader('ETag', $etag)
      ->setHeader('x-Req-ETag', $reqEtag)
      ->setHeader('Content-Type', 'application/json')
      ->setBody($json);
    }
  }
}