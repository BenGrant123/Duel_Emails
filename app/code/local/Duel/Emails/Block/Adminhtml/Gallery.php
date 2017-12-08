<?php

class Duel_Emails_Block_Adminhtml_Gallery extends Mage_Adminhtml_Block_Widget_Grid_Container
{

  public function __construct()
  {
    parent::__construct();

    $this->_removeButton('add');
    $this->_blockGroup = 'duel_emails_adminhtml';
    $this->_controller = 'gallery';
    $this->_headerText = 'Manage Galleries And Emails';
  }

  public function getCreateUrl()
  {
    return $this->getUrl('duel_emails_admin/gallery/edit');
  }

}