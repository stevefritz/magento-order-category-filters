<?php
/**
 * Created by PhpStorm.
 * User: stephen
 * Date: 03/06/16
 * Time: 12:21 AM
 */


class Fritz_Filterorganizer_Block_Layer_View extends Mage_Catalog_Block_Layer_View {

    /**
     * Get all layer filters
     *
     * @return array
     */
    public function getFilters()
    {
        $filters = array();
        if ($categoryFilter = $this->_getCategoryFilter()) {
            $filters[] = $categoryFilter;
        }

        $filterableAttributes = $this->_getFilterableAttributes();
        foreach ($filterableAttributes as $attribute) {
            $filters[$attribute->getAttributeId()] = $this->getChild($attribute->getAttributeCode() . '_filter');
        }
        /* @var $attributeOrders Fritz_Filterorganizer_Model_Resource_Collection */
        $attributeOrders = Mage::getModel('fritz_filterorganizer/attributeorder');
        $attributeOrders = $attributeOrders->getCollection();
        $attributeOrders->addFieldToFilter("category_id", Mage::registry("current_category")->getId());
        $attributeOrders->setOrder("position" , "asc");
        $sortedFilters = [];

        foreach($attributeOrders as $filterPos) {
            if (isset($filters[$filterPos->getAttributeId()])) {
                $sortedFilters[] = $filters[$filterPos->getAttributeId()];
                unset($filters[$filterPos->getAttributeId()]);
            }
        }
        foreach($filters as $filt) {
            $sortedFilters[] = $filt;
        }
        return $sortedFilters;
    }

}