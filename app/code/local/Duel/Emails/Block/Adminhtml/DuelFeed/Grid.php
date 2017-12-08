<?php

use Magento\Backend\Block\Template\Context;

class Duel_Emails_Block_Adminhtml_Duelfeed_Grid extends Mage_Adminhtml_Block_Widget_Grid
{

  protected function _prepareCollection()
  {

    // $storeId = (int)$this->getRequest()->getParam('store', 0);
    $store = $this->_storeManager;

    $collection = Mage::getModel('catalog/product')->getCollection()
    ->addAttributeToSelect(
        'sku'
    )->addAttributeToSelect(
        'name'
    )->addAttributeToSelect(
        'attribute_set_id'
    )->addAttributeToSelect(
        'type_id'
    );

    $collection->addAttributeToSelect('price');
    $collection->joinAttribute('status', 'catalog_product/status', 'entity_id', null, 'inner');
    $collection->joinAttribute('visibility', 'catalog_product/visibility', 'entity_id', null, 'inner');
    // $collection->joinAttribute('gallery_id', 'catalog_product/gallery_id', 'entity_id', null, 'left');
    $collection->joinAttribute('duel_feed_enabled', 'catalog_product/duel_feed_enabled', 'entity_id', null, 'left');

    $collection->joinField(
        'qty',
        'cataloginventory_stock_item',
        'qty',
        'product_id=entity_id',
        '{{table}}.stock_id=1',
        'left'
    );

    $this->setCollection($collection);

    return parent::_prepareCollection();
  }

  public function getRowUrl($row)
  {
    return $this->getUrl(
        'duel_emails_admin/duelfeed/edit',
        array(
          'id' => $row->getId()
        )
    );
  }

  protected function _prepareColumns()
  {
    $this->addColumn(
        'name', array(
            'header' => $this->_getHelper()->__('Name'),
            'type' => 'text',
            'index' => 'name',
        )
    );

    $this->addColumn(
        'sku', array(
            'header' => $this->_getHelper()->__('SKU'),
            'type' => 'text',
            'index' => 'sku'
        )
    );
    /*
    $this->addColumn(
        'gallery_id', array(
            'header' => $this->_getHelper()->__('Gallery ID'),
            'type' => 'text',
            'index' => 'gallery_id',
        )
    );
    */
    $product = Mage::getModel('catalog/product');
    $this->addColumn(
        'type', array(        
                'header' => __('Type'),
                'index' => 'type_id',
                'type' => 'options',
                'options' => Mage_Catalog_Model_Product_Type::getOptionArray(),
        )
    );
    

    $this->addColumn(
        'price', array(   
            'header' => __('Price'),
            'type' => 'price',
            'currency_code' => Mage::app()->getStore()->getCurrentCurrencyCode(),
            'index' => 'price',
            'header_css_class' => 'col-price',
            'column_css_class' => 'col-price'
        )
    );

    $this->addColumn(
        'qty', array(   
            'header' => __('Quantity'),
            'type' => 'number',
            'index' => 'qty'
        )
    );
    
    $this->addColumn(
        'visibility', array(  
            'header' => __('Visibility'),
            'index' => 'visibility',
            'type' => 'options',
            'options' => Mage_Catalog_Model_Product_Visibility::getOptionArray(),
            'header_css_class' => 'col-visibility',
            'column_css_class' => 'col-visibility'
        )
    );

    $this->addColumn(
        'status', array(   
            'header' => __('Status'),
            'index' => 'status',
            'type' => 'options',
            'options' => array("1" => 'Enabled', '2' => 'Disabled', )
        )
    );

    $this->addColumn(
        'duel_feed_enabled', array(
            'header' => __('Show in JSON product feed'),
            'index' => 'duel_feed_enabled',
            'type' => 'options',
            'options' => array('0' => 'Hide', "1" => 'Show')
        )
    );

    return parent::_prepareColumns();
  }

  
  protected function _prepareMassaction()
  {
  $this->setMassactionIdField('entity_id');
  $this->getMassactionBlock()->setFormFieldName('entity_id');
     
  $this->getMassactionBlock()->addItem(
      'bulkRemoveFromFeed', array(
        'label'=> Mage::helper('duel_emails')->__('Remove selected products from JSON feed'),
        'url'  => $this->getUrl('*/*/removeFromFeed', array('_current' => true)),        
        'confirm' => Mage::helper('duel_emails')->__('Are you sure?'),
      )
  )
  ->addItem(
      'bulkAddToFeed', array(
        'label'=> Mage::helper('duel_emails')->__('Add selected products to JSON feed'),
        'url'  => $this->getUrl('*/*/addToFeed', array('_current' => true)),        
        'confirm' => Mage::helper('duel_emails')->__('Are you sure?'),
      )
  );

  return $this;
  }
  

  protected function _getHelper()
  {
    return Mage::helper('duel_emails');
  }

}