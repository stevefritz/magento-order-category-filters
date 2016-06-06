<?php

$installer = $this;
$connection = $installer->getConnection();

$installer->startSetup();

$installer->getConnection()
    ->addColumn("category_attribute_order",
        'attribute_id',
        array(
            'type' => Varien_Db_Ddl_Table::TYPE_INTEGER,
            'nullable' => true,
            'default' => null,
            'comment' => 'Attribute ID'
        )
    );

$installer->getConnection()->addColumn("category_attribute_order",
        'category_id',
        array(
            'type' => Varien_Db_Ddl_Table::TYPE_INTEGER,
            'nullable' => true,
            'default' => null,
            'comment' => 'Category ID'
        )
    );

$installer->endSetup();