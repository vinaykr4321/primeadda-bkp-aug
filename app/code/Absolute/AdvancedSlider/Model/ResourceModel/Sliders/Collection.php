<?php
namespace Absolute\AdvancedSlider\Model\ResourceModel\Sliders;
class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected function _construct()
    {
        $this->_init('Absolute\AdvancedSlider\Model\Sliders','Absolute\AdvancedSlider\Model\ResourceModel\Sliders');
    }
}
