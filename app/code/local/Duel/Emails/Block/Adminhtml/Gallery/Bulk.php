<?php

class Duel_Emails_Block_Adminhtml_Gallery_Bulk extends Mage_Adminhtml_Block_Widget_Form_Container
{

  protected function _construct()
  {
    $this->_blockGroup = 'duel_emails_adminhtml';
    $this->_controller = 'gallery';
    

    $this->_headerText = 'Saving will update all the products you selected from the grid';
  }

  protected function _prepareLayout()
  {

    $entityIds = $this->getData('entityIds');

    $this->_removeButton('delete');
    $this->_removeButton('reset');

    return parent::_prepareLayout();
  }

}