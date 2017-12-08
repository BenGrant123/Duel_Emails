<?php

class Duel_Emails_Model_Resource_Followup extends Mage_Core_Model_Resource_Db_Abstract
{

  protected function _construct()
  {
    $this->_init('duel_emails/followup', 'followup_id');
  }

}