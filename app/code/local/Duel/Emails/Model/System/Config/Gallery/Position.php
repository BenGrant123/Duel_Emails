<?php

class Duel_Emails_Model_System_Config_Gallery_Position
{

  public function toOptionArray()
  {
    return array(
      array(
        'value' => 1,
        'label' => 'Below Add-To-Cart button'
      ),
      array(
        'value' => 2,
        'label' => 'Above product info'
      ),
      array(
        'value' => 3,
        'label' => 'Below product info'
      ),
      array(
        'value' => 4,
        'label' => 'Below product media'
      ),
      array(
        'value' => 5,
        'label' => 'Custom position'
      )
    );
  }

}
