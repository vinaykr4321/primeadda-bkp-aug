<?php
namespace Absolute\AdvancedSlider\Model\ResourceModel;
class Sliders extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    protected function _construct()
    {
        $this->_init('absolute_advancedslider_sliders','slider_id');
    }
}
