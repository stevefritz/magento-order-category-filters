<?php
/**
 * Created by PhpStorm.
 * User: stephen
 * Date: 05/06/16
 * Time: 2:17 PM
 */

class Fritz_Filterorganizer_Model_Resource_Attributeorder_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract {

    protected function _construct()
    {
        $this->_init('fritz_filterorganizer/attributeorder');
    }

}