<?php

class Duel_Emails_Block_Adminhtml_Gallery_Grid extends Mage_Adminhtml_Block_Widget_Grid
{

  protected function _prepareCollection()
  {
    $collection = Mage::getModel('catalog/product')->getCollection();

    $collection->joinAttribute(
        'name',
        'catalog_product/name',
        'entity_id',
        null,
        'inner'
    );
    /* $collection->joinAttribute(
        'gallery_id',
        'catalog_product/gallery_id',
        'entity_id',
        null,
        'left'
    ); */
    $collection->joinAttribute(
        'gallery_color',
        'catalog_product/gallery_color',
        'entity_id',
        null,
        'left'
    );
    $collection->joinAttribute(
        'gallery_background',
        'catalog_product/gallery_background',
        'entity_id',
        null,
        'left'
    );
    $collection->joinAttribute(
        'gallery_columns',
        'catalog_product/gallery_columns',
        'entity_id',
        null,
        'left'
    );
    $collection->joinAttribute(
        'gallery_rows',
        'catalog_product/gallery_rows',
        'entity_id',
        null,
        'left'
    );
    $collection->joinAttribute(
        'duel_page_position',
        'catalog_product/duel_page_position',
        'entity_id',
        null,
        'left'
    );
    $collection->joinAttribute(
        'duel_selector',
        'catalog_product/duel_selector',
        'entity_id',
        null,
        'left'
    );
    $collection->joinAttribute(
        'duel_is_active',
        'catalog_product/duel_is_active',
        'entity_id',
        null,
        'left'
    );
    $collection->joinAttribute(
        'duel_email_enabled',
        'catalog_product/duel_email_enabled',
        'entity_id',
        null,
        'left'
    );
    $this->setCollection($collection);

    return parent::_prepareCollection();
  }

  public function getRowUrl($row)
  {
    return $this->getUrl(
        'duel_emails_admin/gallery/edit',
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
    $this->addColumn(
        'gallery_color', array(
            'header' => $this->_getHelper()->__('Gallery Color'),
            'width' => '5px',
            'type' => 'text',
            'index' => 'gallery_color',
        )
    );

    $this->addColumn(
        'gallery_background', array(
            'header' => $this->_getHelper()->__('Gallery Background Color'),
            'width' => '5px',
            'type' => 'text',
            'index' => 'gallery_background',
        )
    );

    $this->addColumn(
        'gallery_columns', array(
            'header' => $this->_getHelper()->__('Gallery Columns'),
            'type' => 'options',
            'index' => 'gallery_columns',
            'options' => array('0'=>'Use default','1' => '1','2' => '2','3' => '3','4' => '4', '5' => '5')
        )
    );

    $this->addColumn(
        'gallery_rows', array(
            'header' => $this->_getHelper()->__('Gallery Rows'),
            'type' => 'options',
            'index' => 'gallery_rows',
            'options' => array(
                '0'=>'Use default',
                '1' => '1',
                '2' => '2',
                '3' => '3',
                '4' => '4', 
                '5' => '5', 
                '6' => '6', 
                '7' => '7', 
                '8' => '8', 
                '9' => '9', 
                '11' => '11',
                '12' => '12',
                '13' => '13',
                '14' => '14', 
                '15' => '15'
            )
        )
    );

    $this->addColumn(
        'duel_page_position', array(
            'header' => $this->_getHelper()->__('Position Gallery on page (standard options)'),
            'type' => 'options',
            'width' => '5px',
            'index' => 'duel_page_position',
            'options' => array(
                '0'=>'Use default',
                '1' => 'Below Add-To-Cart button',
                '2' => 'Above product info',
                '3' => 'Below product info',
                '4' => 'Below product media', 
                '5' => 'Custom position'
            )
        )
    );

    $this->addColumn(
        'duel_selector', array(
            'header' => $this->_getHelper()->__('Position Gallery on page (custom selector)'),
            'type' => 'text',
            'index' => 'duel_selector',
        )
    );

    $this->addColumn(
        'duel_is_active', array(
            'header' => $this->_getHelper()->__('Show gallery on product page'),
            'type' => 'options',
            'width' => '5px',
            'index' => 'duel_is_active',
            'options' => array('0' => 'Disabled', "1" => 'Enabled')
        )
    );

    $this->addColumn(
        'duel_email_enabled', array(
            'header' => $this->_getHelper()->__('Enable Duel post-purchase email'),
            'type' => 'options',
            'width' => '5px',
            'index' => 'duel_email_enabled',
            'options' => array('0' => 'Disabled', "1" => 'Enabled')
   
        )
    );

    
    $this->addColumn(
        'action', array(
        'header' => $this->_getHelper()->__('Action'),
        'width' => '50px',
        'type' => 'action',
        'actions' => array(
          array(
            'caption' => $this->_getHelper()->__('Edit'),
            'url' => array(
              'base' => 'duel_emails_admin/gallery/edit'
            ),
            'field' => 'id'
          ),
        ),
        'filter' => false,
        'sortable' => false,
        'index' => 'entity_id'
        )
    );
    

    return parent::_prepareColumns();
  }

  
  protected function _prepareMassaction()
  {
  $this->setMassactionIdField('entity_id');
  $this->getMassactionBlock()->setFormFieldName('entity_id');
     
  $this->getMassactionBlock()->addItem(
      'bulk', array(
        'label'=> Mage::helper('duel_emails')->__('Bulk edit selected products'),
        'url'  => $this->getUrl('*/*/bulk', array('_current' => true)),        
        'confirm' => Mage::helper('duel_emails')->__('Are you sure?'),
      )
  );

  $this->getMassactionBlock()->addItem(
      'resetdefaults', array(
        'label'=> Mage::helper('duel_emails')->__('Reset values to "System>Configuration>Duel" defaults'),
        'url'  => $this->getUrl('*/*/resetDefaults', array('' => '')),        
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