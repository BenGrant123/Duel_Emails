<?php

class Duel_Emails_Block_Adminhtml_Gallery_Edit_Form extends Mage_Adminhtml_Block_Widget_Form
{

  protected function _prepareForm()
  {

    $entityIds = Mage::registry("mass_edit_ids");

    $boolArray = array('0'=>'Disabled','1' => 'Enabled');
    $rowsArray = array(
      '0'=>'Use default',
      '1' => '1',
      '2' => '2',
      '3' => '3',
      '4' => '4',
      '5' => '5',
      '6' => '6', 
      '7' => '7',
      '8' => '8',
      '9' => '9',
      '10' => '10',
      '11' => '11',
      '12' => '12',
      '13' => '13',
      '14' => '14',
      '15' => '15'
    );
    $columnsArray = array('0'=>'Use default','1' => '1','2' => '2','3' => '3','4' => '4','5' => '5');
    $positionsArray = array(
      '0'=>'Use default',
      '1' => 'Below Add-To-Cart button',
      '2' => 'Above product info',
      '3' => 'Below product info',
      '4' => 'Below product media'
    );
    $ignoreAttribute = array("-1" => "Don't update this attribute");
    
    

    $form = new Varien_Data_Form(
        array(
            'id' => 'edit_form',
            'action' => $this->getUrl(
                'duel_emails_admin/gallery/edit',
                array(
                  '_current' => 'true',
                  'continue' => 0
                )
            ),
            'method' => 'post'
          )
    );
    $form->setUseContainer(true);
    $this->setForm($form);

    $fieldset = $form->addFieldset(
        'duel_gallery',
        array(
          'legend' => $entityIds 
          ? $this->__('Bulk edit gallery attributes') 
          : $this->__('Gallery settings')
        )
    );

    $fieldsetFollowup = $form->addFieldset(
        'duel_followup',
        array(
          'legend' => $entityIds 
          ? $this->__('Bulk edit follow-up email attributes') 
          : $this->__('Follow-up email settings')
        )
    );

    if (!$entityIds) {
      /*
        $this->_addFieldsToFieldset(
            $fieldset, array(
                'gallery_id' => array(
                    'label' => $this->__('Gallery ID'),
                    'input' => 'text',
                    'required' => false
                ),
            )
        );
        */
    } else {
        $fieldset->addField(
            'gallery_bulk_edit_ids', 'hidden', array(
                  'name'  => 'gallery_bulk_edit_ids',
                  'required' => false,
                  'value' => json_encode($entityIds)
            )
        );
        $boolArray = $ignoreAttribute + $boolArray;
        $columnsArray = $ignoreAttribute + $columnsArray;
        $rowsArray = $ignoreAttribute + $rowsArray;
        $positionsArray = $ignoreAttribute + $positionsArray;
    }

      $this->_addFieldsToFieldset(
          $fieldset, array(
            'duel_is_active' => array(
              'label' => $this->__('Show Gallery On Product Page'),
              'input' => 'select',
              'required' => false,
              'values' => $boolArray,
            ),
            'gallery_rows' => array(
              'label' => $this->__('Gallery Rows'),
              'input' => 'select',
              'required' => false,
              'values' => $rowsArray,
            ),
            'gallery_columns' => array(
              'label' => $this->__('Gallery Columns'),
              'input' => 'select',
              'required' => false,
              'values' => $columnsArray,
            ),
            'duel_page_position' => array(
              'label' => $this->__('Gallery Position (standard layout options)'),
              'input' => 'select',
              'required' => false,
              'values' => $positionsArray,
            ),
            'duel_selector' => array(
              'label' => $this->__('Gallery Position (custom CSS selector)'),
              'input' => 'text',
              'required' => false
            ),
            'gallery_color' => array(
              'label' => $this->__('Gallery Color'),
              'input' => 'text',
              'class' => 'color {required:false, adjust:false, hash:true}',
              'required' => false,
            ),
            'gallery_background' => array(
              'label' => $this->__('Gallery Background Color'),
              'input' => 'text',
              'class' => 'color {required:false, adjust:false, hash:true}',
              'required' => false,
            ),
          )
      );

      $this->_addFieldsToFieldset(
          $fieldsetFollowup, array(
            'duel_email_enabled' => array(
              'label' => $this->__('Enable post-purchase email'),
              'input' => 'select',
              'required' => false,
              'values' => $boolArray,
            ),
          )
      );


    if ($entityIds) {
        $form->addValues(
            array(
                'duel_is_active' => '-1',
                'duel_email_enabled' => '-1',
                'gallery_rows' => '-1',
                'gallery_columns' => '-1',
                'duel_page_position' => '-1',
                'duel_selector' => 'Don\'t update this attribute',
                'gallery_color' => 'Don\'t update this attribute',
                'gallery_background' => 'Don\'t update this attribute'
            )
        );
    } else {
        $this->_addFieldsToFieldset(
            $fieldsetFollowup, array(
                'duel_cta_text' => array(
                    'label' => $this->__('Follow up email call-to-action text (customise)'),
                    'input' => 'textarea',
                    'required' => false,
                ),
            )
        );
    }

    return $this;
  }

  protected function _addFieldsToFieldset(Varien_Data_Form_Element_Fieldset $fieldset, $fields)
  {
    $requestData = new Varien_Object($this->getRequest()->getPost('galleryData'));

    foreach ($fields as $name => $_data) {
      if ($requestValue = $requestData->getData($name)) {
        $_data['value'] = $requestValue;
      }

      $_data['name'] = "galleryData[$name]";

      $_data['title'] = $_data['label'];

      if (!array_key_exists('value', $_data)) {
        $_data['value'] = $this->_getProduct()->getData($name);
      }

      $fieldset->addField($name, $_data['input'], $_data);
    }

    return $this;
  }

  protected function _getProduct()
  {
    if (!$this->hasData('product')) {
      $product = Mage::registry('current_product');

      if (!$product) {
        $product = Mage::getModel('catalog/product');
      }

      $this->setData('product', $product);
    }

    return $this->getData('product');
  }

}
