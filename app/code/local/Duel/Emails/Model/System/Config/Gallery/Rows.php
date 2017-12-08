<?php

class Duel_Emails_Model_System_Config_Gallery_Rows
{

  public function toOptionArray()
  {
    return $this->generateOptions(15);
  }

  protected function generateOptions($number)
  {
    $options = array(
      array(
        'value' => 0,
        'label' => 'Auto'
      )
    );
    for ($i=1; $i <= $number; $i++) { 
      $option = array(
        'value' => $i,
        'label' => (string)$i
      );
      array_push($options, $option);
    }
    
    return $options;
  }

}