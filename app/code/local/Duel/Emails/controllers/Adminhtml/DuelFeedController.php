<?php

class Duel_Emails_Adminhtml_DuelFeedController extends Mage_Adminhtml_Controller_Action
{

  public function indexAction()
  {
    $duelfeedBlock = $this->getLayout()
      ->createBlock('duel_emails_adminhtml/duelfeed');

      $this->loadLayout()
        ->_addContent($duelfeedBlock)
        ->renderLayout();
  }

  public function removeFromFeedAction()
  {
    $entityIds = $this->getRequest()->getPost('entity_id');

    if (!$entityIds) {
      $this->_getSession()->addError($this->__('No product(s) selected to bulk edit.'));
      return $this->_redirect('duel_emails_admin/duelfeed/index');
    }

    $duelfeedData = array(
        'duel_feed_enabled' => false
    );
    $storeId = Mage::app()->getStore()->getStoreId();
    $action = Mage::getModel("catalog/product_action");
    $action->updateAttributes($entityIds, $duelfeedData, $storeId);

    $this->_getSession()->addSuccess($this->__('Products removed from JSON feed.'));

    return $this->_redirect('duel_emails_admin/duelfeed/index');

  }

  public function addToFeedAction()
  {
    $entityIds = $this->getRequest()->getPost('entity_id');

    if (!$entityIds) {
      $this->_getSession()->addError($this->__('No product(s) selected to bulk edit.'));
      return $this->_redirect('duel_emails_admin/duelfeed/index');
    }

    $duelfeedData = array(
        'duel_feed_enabled' => true
    );
    $storeId = Mage::app()->getStore()->getStoreId();
    $action = Mage::getModel("catalog/product_action");
    $action->updateAttributes($entityIds, $duelfeedData, $storeId);

    $this->_getSession()->addSuccess($this->__('Products added to JSON feed.'));

    return $this->_redirect('duel_emails_admin/duelfeed/index');

  }

  public function editAction()
  {
    $storeId = Mage::app()->getStore()->getStoreId();
    $product = Mage::getModel('catalog/product');

    $singleProductId = $this->getRequest()->getParam('id', false);
    $bulkEditIds = $this->getRequest()->getPost('bulk_edit_ids');

    if ($singleProductId) {
      $product->load($singleProductId);

      if (!$product->getId()) {
        $this->_getSession()->addError($this->__('This product no longer exists.'));
        return $this->_redirect('duel_emails_admin/duelfeed/index');
      }
    }
    
    $duelfeedData = $this->getRequest()->getPost('duelfeedData');

    if ($duelfeedData) {
      try {
        $product->addData($duelfeedData);
        $product->save();

        $this->_getSession()->addSuccess($this->__('The product has been saved.'));

        return $this->_redirect(
            'duel_emails_admin/duelfeed/index',
            array('id' => $product->getId())
        );
      } catch (Exception $e) {
        Mage::logException($e);
        $this->_getSession()->addError($e->getMessage());
      }
    }

    Mage::register('current_product', $product);

    $duelfeedEditBlock = $this->getLayout()->createBlock('duel_emails_adminhtml/duelfeed_edit');

    $this->loadLayout()
      ->_addContent($duelfeedEditBlock)
      ->renderLayout();
  }

  protected function _isAllowed()
  {
    return Mage::getSingleton('admin/session')->isAllowed('system/config');
  }

}