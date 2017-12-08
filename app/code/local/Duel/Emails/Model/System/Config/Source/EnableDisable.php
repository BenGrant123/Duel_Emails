<?php

class Duel_Emails_Model_System_Config_Source_EnableDisable
{

  public function toOptionArray() 
  {
    return array(
      array(
        'value' => 0,
        'label' => 'Disabled'
      ),
      array(
        'value' => 1,
        'label' => 'Enabled'
      )
    );
  }

}