<?php

class Duel_Emails_Model_Resource_Followup_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract
{

  public function _construct()
  {
    $this->_init('duel_emails/followup');
  }

}