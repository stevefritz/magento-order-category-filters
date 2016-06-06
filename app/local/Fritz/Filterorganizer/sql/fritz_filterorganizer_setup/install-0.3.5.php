<?php
/**
 * Created by PhpStorm.
 * User: stephen
 * Date: 03/06/16
 * Time: 12:13 AM
 */ 
/* @var $installer Mage_Core_Model_Resource_Setup */
$installer = $this;

$installer->startSetup();

$table = $installer->getConnection()
    ->newTable("category_attribute_order")
    ->addColumn('category_attribute_order_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'identity'  => true,
        'unsigned'  => true,
        'nullable'  => false,
        'primary'   => true,
    ), 'Id')
    ->addColumn('position', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'nullable'  => false,
    ), 'Position');

$installer->getConnection()->createTable($table);



$installer->endSetup();
