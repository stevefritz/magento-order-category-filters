<?php

/**
 * Created by PhpStorm.
 * User: stephen
 * Date: 05/06/16
 * Time: 6:06 AM
 */
class Fritz_Filterorganizer_Model_Category_Observer
{
    public function saveCategoryData($observer)
    {
        $categoryAttributes = Mage::helper('adminhtml/js')->decodeGridSerializedInput( Mage::app()->getRequest()->
        getPost('categoryAttributes'));
        if (!Mage::registry("current_category")) {
            return;
        }
        $categoryId = Mage::registry("current_category")->getId();
        foreach ($categoryAttributes as $row) {
            $model = Mage::getModel('fritz_filterorganizer/attributeorder');
            $dataTmp = [
                "category_id" =>$categoryId,

                "attribute_id" => $row['attribute_id'],
                "position" => $row['position'] ?: 9999
            ];
            if ($row['category_attribute_order_id']) {
                $dataTmp["category_attribute_order_id"] = $row['category_attribute_order_id'];
            }
            Mage::log($dataTmp);
            $model->setData($dataTmp)->save();
        }




    }

    public function addCategoryEssentialBlock($observer) {
        $tabs = $observer->getEvent()->getTabs();
        $content = $tabs->getLayout()->createBlock(
            'fritz_filterorganizer/Adminhtml_Catalog_Category_Tab_Attributeorder',
            'category.order.attributes')->toHtml();

        // TODO Convert this to be loaded via XML.
        // Serializer allows for grids to be saved
        // A checkbox is required for this to work.
        $serializer = $tabs->getLayout()->createBlock(
            'adminhtml/widget_grid_serializer',
            'category.essential.grid.serializer'
        );
        $serializer->initSerializerBlock(
            'category.order.attributes',
            'getAttributes',
            'categoryAttributes',
            'category_essentials'
        );
        $serializer->addColumnInputName('attribute_id');
        $serializer->addColumnInputName('position');
        $serializer->addColumnInputName('category_attribute_order_id');
        $content .= $serializer->toHtml();
        $tabs->addTab(
            'OrderAttributes',
            array(
                'label'     => Mage::helper('catalog')->__('Order Attributes'),
                'content' => $content,
            )
        );
        return $this;

    }
}