<?php

class Duel_Emails_Block_Gallery extends Mage_Catalog_Block_Product_Abstract
{

  public $product;

  public function __construct()
  {
    $this->product = $this->getProduct();
  }

  public function getGallery()
  {

    $gallery = array();

    $gallery['product'] = Mage::getStoreConfig('duelemails_options/emails/brand_short_id') . '/' . $this->product['sku'];

    $gallery['color'] = $this->product['gallery_color'] 
      ? $this->product['gallery_color'] 
      : ("#" . Mage::getStoreConfig('duelemails_options/galleries/default_color'));

    $gallery['background'] = $this->product['gallery_background'] 
      ? $this->product['gallery_background'] 
      : ("#" . Mage::getStoreConfig('duelemails_options/galleries/default_background'));

    $gallery['rows'] = Mage::getStoreConfig('duelemails_options/galleries/default_rows');
    $gallery['columns'] = Mage::getStoreConfig('duelemails_options/galleries/default_columns');

    if ((int)$this->product['gallery_rows'] > 0) {
      $gallery['rows'] = $this->product['gallery_rows'];
    }
    
    if ((int)$this->product['gallery_columns'] > 0) {
      $gallery['columns'] = $this->product['gallery_columns'];
    }

    $gallery['position'] = $this->product['duel_page_position'];
    $gallery['selector'] = $this->product['duel_selector'];

    if ($this->product['duel_is_active'] And Mage::getStoreConfig('duelemails_options/galleries/show_galleries')) {
      $gallery['active'] = true;
    } else {
      $gallery['active'] = false;
    }

    $gallery['default_position'] = Mage::getStoreConfig('duelemails_options/galleries/default_position');
    $gallery['default_selector'] = Mage::getStoreConfig('duelemails_options/galleries/custom_position');

    return json_encode($gallery);

  }

}