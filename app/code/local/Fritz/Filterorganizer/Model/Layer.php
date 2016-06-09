<?php
/**
 * Created by PhpStorm.
 * User: stephen
 * Date: 03/06/16
 * Time: 12:21 AM
 */


class Fritz_Filterorganizer_Model_Layer extends Mage_Catalog_Model_Layer {


    /**
     * Get collection of all filterable attributes for layer products set
     *
     * @return Mage_Catalog_Model_Resource_Eav_Mysql4_Attribute_Collection
     */
    public function getFilterableAttributes()
    {

        $setIds = $this->_getSetIds();
        if (!$setIds) {
            return array();
        }

        $currentCategoryId = (Mage::registry("current_category")->getId());

        $orderClause = array('CASE entity_attribute.attribute_id');
        $attributeOrder = [193,209,177]; // TODO Replace this with a call to gets this cats atrributes
        foreach ($attributeOrder as $it => $attrId) {
            $orderClause[] = 'WHEN ' . $attrId . ' THEN ' . $it;
        }

        $orderClause[] = "ELSE 999";
        $orderClause[] = "END;";
        $orderClause = implode(' ', $orderClause) . " , position ASC";

        /** @var $collection Mage_Catalog_Model_Resource_Product_Attribute_Collection */
        $collection = Mage::getResourceModel('catalog/product_attribute_collection');
        $collection
            ->setItemObjectClass('catalog/resource_eav_attribute')
            ->setAttributeSetFilter($setIds)
            ->addStoreLabel(Mage::app()->getStore()->getId());

        $collection->getSelect()
            ->order(new Zend_Db_Expr($orderClause)) ;

        $collection = $this->_prepareAttributeCollection($collection);
        $collection->load();

        return $collection;
    }


}