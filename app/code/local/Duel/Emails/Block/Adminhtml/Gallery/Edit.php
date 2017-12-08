<?php

class Duel_Emails_Block_Adminhtml_Gallery_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{

  protected function _construct()
  {
    $this->_blockGroup = 'duel_emails_adminhtml';
    $this->_controller = 'gallery';
    

    $this->_headerText = 'Edit Duel gallery & follow-up email';
  }

  protected function _prepareLayout()
  {
    $this->_removeButton('delete');
    $this->_removeButton('reset');

    return parent::_prepareLayout();
  }

}