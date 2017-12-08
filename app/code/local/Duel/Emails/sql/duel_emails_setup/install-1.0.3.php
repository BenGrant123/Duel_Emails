<?php

class newTextAttribute
{
    public function addAttribute($setup, $name, $label)
    {
        $setup->addAttribute(
            'catalog_product', $name, array(
                'group'           => 'Duel Gallery',
                'label'           => $label,
                'input'           => 'text',
                'type'            => 'varchar',
                'required'        => 0,
                'visible'         => 0,
                'visible_on_front'=> 1,
                'filterable'      => 0,
                'searchable'      => 0,
                'comparable'      => 0,
                'user_defined'    => 1,
                'is_configurable' => 0,
                'global'          => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
                'note'            => ''
            )
        );
    }
}

$this->startSetup();

$installer = Mage::getResourceModel('catalog/setup', 'catalog_setup');

$installer->addAttributeGroup('catalog_product', 'Default', 'Duel Gallery', 1000);

$installer->removeAttribute('catalog_product', 'gallery_id');
$installer->removeAttribute('catalog_product', 'gallery_rows');
$installer->removeAttribute('catalog_product', 'gallery_columns');
$installer->removeAttribute('catalog_product', 'gallery_color');
$installer->removeAttribute('catalog_product', 'gallery_background');
$installer->removeAttribute('catalog_product', 'duel_selector');
$installer->removeAttribute('catalog_product', 'duel_is_active');
$installer->removeAttribute('catalog_product', 'duel_email_enabled');
$installer->removeAttribute('catalog_product', 'duel_page_position');
$installer->removeAttribute('catalog_product', 'duel_cta_text');
$installer->removeAttribute('catalog_product', 'duel_feed_enabled');

newTextAttribute::addAttribute($installer, 'gallery_id', 'Gallery ID');
newTextAttribute::addAttribute($installer, 'gallery_color', 'Gallery Color');
newTextAttribute::addAttribute($installer, 'gallery_background', 'Gallery Background Color');
newTextAttribute::addAttribute($installer, 'gallery_columns', 'Gallery Columns');
newTextAttribute::addAttribute($installer, 'gallery_rows', 'Gallery Rows');
newTextAttribute::addAttribute($installer, 'duel_selector', 'Position gallery on page (custom selector)');
newTextAttribute::addAttribute($installer, 'duel_cta_text', 'Follow up email call-to-action text');


$installer->addAttribute(
    'catalog_product', 'duel_is_active', array(
          'group'           => 'Duel Gallery',
          'label'           => 'Show gallery on product page',
          'input'           => 'boolean',
          'type'            => 'int',
          'required'        => 0,
          'visible'         => 0,
          'visible_on_front'=> 1,
          'filterable'      => 0,
          'searchable'      => 0,
          'comparable'      => 0,
          'user_defined'    => 1,
          'is_configurable' => 0,
          'global'          => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
          'default'         => false,
    )
);

$installer->addAttribute(
    'catalog_product', 'duel_email_enabled', array(
        'group'           => 'Duel Gallery',
        'label'           => 'Enable Duel post-purchase email',
        'input'           => 'boolean',
        'type'            => 'int',
        'required'        => 0,
        'visible'         => 0,
        'visible_on_front'=> 1,
        'filterable'      => 0,
        'searchable'      => 0,
        'comparable'      => 0,
        'user_defined'    => 1,
        'is_configurable' => 0,
        'global'          => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
        'default'         => false,
    )
);

$installer->addAttribute(
    'catalog_product', 'duel_feed_enabled', array(
        'group'           => 'Duel Gallery',
        'label'           => 'Show on JSON product feed',
        'input'           => 'boolean',
        'type'            => 'int',
        'required'        => 0,
        'visible'         => 0,
        'visible_on_front'=> 1,
        'filterable'      => 0,
        'searchable'      => 0,
        'comparable'      => 0,
        'user_defined'    => 1,
        'is_configurable' => 0,
        'global'          => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
        'default'         => true,
    )
);

$installer->addAttribute(
    'catalog_product', 'duel_page_position', array(
        'group'           => 'Duel Gallery',
        'label'           => 'Position gallery on page',
        'input'           => 'select',
        'type'            => 'varchar',
        'required'        => 0,
        'visible'         => 0,
        'visible_on_front'=> 1,
        'filterable'      => 0,
        'searchable'      => 0,
        'comparable'      => 0,
        'user_defined'    => 1,
        'is_configurable' => 0,
        'global'          => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
    )
);


$connection = $this->getConnection();

$followupTable = $this->getTable('duel_emails/followup');
if ($connection->isTableExists($followupTable) === true) {
  $connection->dropTable($followupTable);
}

$table = $connection
  ->newTable($followupTable)
  ->addColumn(
      'followup_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null,
      array(
        'auto_increment' => true,
        'unsigned' => true,
        'nullable' => false,
        'primary'  => true,
      ), 'Unique identifier'
  )
  ->addColumn(
      'order_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, 
      array(
        'nullable' => false
      ), 'Order ID'
  )
  ->addColumn(
      'completed', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, 
      array(
        'default' => Varien_Db_Ddl_Table::TIMESTAMP_INIT
      ), 'invoice was created'
  );

$connection->createTable($table);
$installer->getConnection()->addIndex(
    $installer->getTable('duel_emails/followup'),
    $installer->getIdxName('followup', array('followup_id')),
    array('followup_id')
);

$randomNumber = bin2hex(openssl_random_pseudo_bytes(10));
Mage::getConfig()->saveConfig('duelemails_options/galleries/hash', $randomNumber);

$this->endSetup();




