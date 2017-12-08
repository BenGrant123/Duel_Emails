<?php

class Duel_Emails_Block_Adminhtml_DuelFeed extends Mage_Adminhtml_Block_Widget_Grid_Container
{

  public function __construct()
  {
    parent::__construct();

    $this->_removeButton('add');
    $this->_blockGroup = 'duel_emails_adminhtml';
    $this->_controller = 'duelfeed';
    $duelFeedUrl = Mage::getStoreConfig('web/secure/base_url')
    . 'feeds/duel/' . Mage::getStoreConfig('duelemails_options/galleries/hash');
    $this->_headerText = 'Duel JSON product feed URL:<br/><a style="color: blue;" href="'
    . $duelFeedUrl . '">' . $duelFeedUrl . '</a>';
  }

  public function getCreateUrl()
  {
    return $this->getUrl('duel_emails_admin/duelfeed/edit');
  }

}