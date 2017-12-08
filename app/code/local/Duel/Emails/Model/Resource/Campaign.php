<?php

class Duel_Emails_Model_Resource_Campaign extends Mage_Core_Model_Resource_Db_Abstract
{

  protected function _construct()
  {
    $this->_init('duel_emails/campaign', 'entity_id');
  }

}