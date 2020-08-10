<?php
namespace Absolute\AdvancedSlider\Model\ResourceModel\Slides;
class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected $_idFieldName = 'slide_id';

    protected function _construct()
    {
        $this->_init('Absolute\AdvancedSlider\Model\Slides','Absolute\AdvancedSlider\Model\ResourceModel\Slides');
    }
}
