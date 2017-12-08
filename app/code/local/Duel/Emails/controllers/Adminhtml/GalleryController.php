<?php

class Duel_Emails_Adminhtml_GalleryController extends Mage_Adminhtml_Controller_Action
{

  public function indexAction()
  {
    $galleryBlock = $this->getLayout()
      ->createBlock('duel_emails_adminhtml/gallery');

      $this->loadLayout()
        ->_addContent($galleryBlock)
        ->renderLayout();
  }

  public function bulkAction()
  {
    $entityIds = $this->getRequest()->getPost('entity_id');

    if (!$entityIds) {
      $this->_getSession()->addError($this->__('No product(s) selected to bulk edit.'));
      return $this->_redirect('duel_emails_admin/gallery/index');
    }

    Mage::register('mass_edit_ids', $entityIds);

    $galleryMassEditBlock = $this->getLayout()
      ->createBlock('duel_emails_adminhtml/gallery_bulk')
      ->setData('entityIds', $entityIds);
    $this->loadLayout()
      ->_addContent($galleryMassEditBlock)
      ->renderLayout();

  }

  

  public function resetDefaultsAction()
  {
    $storeId = Mage::app()->getStore()->getStoreId();
    $edits = array(
      'gallery_color' => Mage::getStoreConfig('duelemails_options/galleries/default_color'),
      'gallery_background' => Mage::getStoreConfig('duelemails_options/galleries/default_background'),
      'gallery_rows' => Mage::getStoreConfig('duelemails_options/galleries/default_rows'),
      'gallery_columns' => Mage::getStoreConfig('duelemails_options/galleries/default_columns'),
      'duel_page_position' => Mage::getStoreConfig('duelemails_options/galleries/default_position'),
      'duel_selector' => Mage::getStoreConfig('duelemails_options/galleries/default_selector'),
      'duel_is_active' => Mage::getStoreConfig('duelemails_options/galleries/show_galleries'),
      'duel_email_enabled' =>  Mage::getStoreConfig('duelemails_options/emails/active')
    );

    $entityIds = $this->getRequest()->getPost('entity_id');

    $action = Mage::getModel("catalog/product_action");
    $action->updateAttributes($entityIds, $edits, $storeId);

    return $this->_redirect('duel_emails_admin/gallery/index');
  }

  public function editAction()
  {
    $storeId = Mage::app()->getStore()->getStoreId();
    $product = Mage::getModel('catalog/product');

    $singleProductId = $this->getRequest()->getParam('id', false);
    $bulkEditIds = $this->getRequest()->getPost('gallery_bulk_edit_ids');

    if ($singleProductId) {
      $product->load($singleProductId);

      if (!$product->getId()) {
        $this->_getSession()->addError($this->__('This product no longer exists.'));
        return $this->_redirect('duel_emails_admin/gallery/index');
      }
    }
    
    $galleryData = $this->getRequest()->getPost('galleryData');
    $bulkEditIds = $this->getRequest()->getPost('gallery_bulk_edit_ids');


    if ($bulkEditIds And $galleryData) {
      $bulkEditIds = json_decode($bulkEditIds);
      $galleryData = array_diff($galleryData, array("-1", "Don't update this attribute"));

      $action = Mage::getModel("catalog/product_action");
      $action->updateAttributes($bulkEditIds, $galleryData, $storeId);

      $this->_getSession()->addSuccess($this->__('The products have been saved.'));
      return $this->_redirect('duel_emails_admin/gallery/index');
    }
    

    if (!$bulkEditIds And $galleryData) {
      try {
        $product->addData($galleryData);
        $product->save();

        $this->_getSession()->addSuccess($this->__('The product has been saved.'));

        return $this->_redirect(
            'duel_emails_admin/gallery/index',
            array('id' => $product->getId())
        );
      } catch (Exception $e) {
        Mage::logException($e);
        $this->_getSession()->addError($e->getMessage());
      }
    }

    Mage::register('current_product', $product);

    $galleryEditBlock = $this->getLayout()->createBlock('duel_emails_adminhtml/gallery_edit');

    $this->loadLayout()
      ->_addContent($galleryEditBlock)
      ->renderLayout();
  }

  protected function _isAllowed()
  {
    return Mage::getSingleton('admin/session')->isAllowed('system/config');
  }

}