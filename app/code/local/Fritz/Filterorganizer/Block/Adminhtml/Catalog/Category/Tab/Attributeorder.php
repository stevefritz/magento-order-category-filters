<?php
/**
 * Created by PhpStorm.
 * User: stephen
 * Date: 03/06/16
 * Time: 1:23 AM
 */


class Fritz_Filterorganizer_Block_Adminhtml_Catalog_Category_Tab_Attributeorder extends Mage_Adminhtml_Block_Widget_Grid {


    public function __construct()
    {
        $this->_blockGroup = 'fritz_filterorganizer';
        $this->_controller = 'adminhtml_attributeorder';
        $this->_headerText = $this->__('Baz');
        $this->setUseAjax(true);
        parent::__construct();
    }

    public function getCategory()
    {
        return Mage::registry('category');
    }

    protected function _addColumnFilterToCollection($column)
    {

    }

    public function getAttributes() {

        $collection = $this->_getAttributeCollection();
        $data = [];
        foreach($collection as $attr) {

            $data[ $attr->getAttributeId() ] = [
                "attribute_id" => $attr->getAttributeId(),
                "category_attribute_order_id" => $attr->getCategoryAttributeOrderId(),
                "position" => $attr->getPosition() ?: 0

            ];

        }

    return $data;
    }

    protected function _getAttributeCollection() {

        $category = $this->getCategory();
        if (!$category->getId()) {
            return new Varien_Data_Collection();
        }

	try {
            $products = $category->getProductCollection();
            $setIds = $products->getSetIds();
            // collect = filters
            $collection = Mage::getResourceModel('catalog/product_attribute_collection');
            $collection->getSelect()
                ->reset(Zend_Db_Select::COLUMNS)
                ->columns('main_table.*')
                ->columns('attribute_id')
                ->columns('category_attribute_order.category_attribute_order_id')
                ->columns('category_attribute_order.position')
                ->joinLeft('category_attribute_order',
                    "main_table.attribute_id = category_attribute_order.attribute_id" .
                    (  $this->getCategory()->getId() ? " and category_id = " . $this->getCategory()->getId() : "")

                );

            $collection
                ->setItemObjectClass('catalog/resource_eav_attribute')
                ->setAttributeSetFilter($setIds)
                ->addStoreLabel(Mage::app()->getStore()->getId())
                ->addFieldToFilter('additional_table.is_filterable', array('gt' => 0))
                ->setOrder('category_attribute_order.position', 'ASC');
          return $collection;
        } catch (Exception $e) {
            var_dump($e->getMessage());
        }

    }
    protected function _prepareCollection()
    {
        $this->setCollection($this->_getAttributeCollection());

        parent::_prepareCollection();
        return $this;
    }

    protected function _prepareColumns()
    {

        $this->addColumn('attribute[id][]', array(
            'header' => ('Attribute ID'),
            'index'  => 'attribute_id',
            'field_name'         => 'attribute[id][]',
        ));
        $this->addColumn('attribute[code][]', array(
            'header' => ('Attribtue Code'),
            'index'  => 'attribute_code',
              'field_name'         => 'attribute[code][]',
        ));
        $this->addColumn('frontend_label', array(
            'header' => ('Label ID'),
            'index'  => 'frontend_label'
        ));
        $this->addColumn('position', array(
            'header'    => Mage::helper('catalog')->__('Position'),
            'width'     => '1',
            'type'      => 'number',
            'index'     => 'position',
            'field_name'         => 'attribute[position][]',
            'editable'  => true

        ));

        $this->addColumn('attribute_id', array(
            'header' => Mage::helper('catalog')->__('attribute_id'),
            'index' => 'attribute_id',
            'column_css_class'=>'no-display',//this sets a css class to the column row item
            'header_css_class'=>'no-display',//this sets a css class to the column header
            'width'     => '1',
            'type'      => 'number',
            'editable'  => true
        ));


        $this->addColumn('category_attribute_order_id', array(
            'header' => Mage::helper('catalog')->__('attribute_id'),
            'index' => 'category_attribute_order_id',
            'column_css_class'=>'no-display',//this sets a css class to the column row item
            'header_css_class'=>'no-display',//this sets a css class to the column header
            'width'     => '1',
            'type'      => 'number',
            'editable'  => true
        ));


        $this->addColumn('in_products', array(
            'header_css_class'  => 'a-center',
            'type'              => 'checkbox',
            'name'              => 'in_products',
            'values'            => $this->getEnabledAttributes(),
            'column_css_class'=>'no-display',//this sets a css class to the column row item
            'header_css_class'=>'no-display',//this sets a css class to the column header
            'align'             => 'center',
            'index'             => 'attribute_id'
        ));

        return parent::_prepareColumns();
    }

    protected function getEnabledAttributes() {
        $collection = $this->_getAttributeCollection();

        $data = [];
        foreach($collection as $attr) {

            $data[$attr->getAttributeId()] = [
                "position" => $attr->getPosition() ?: 0

            ];

        }

        return array_keys($data);

    }

    public function getAttributeOrders()
    {
        $products = array();
        if (Mage::registry('turnkeye_adminform')) {
            foreach (Mage::registry('turnkeye_adminform')->getProductsPosition() as $id => $pos) {
                $products[$id] = array('position' => $pos);
            }
        }

        return ["1" => ["attribute_id" => 0, "position" => 0 ]];
    }

}
