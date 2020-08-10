<?php
namespace Absolute\AdvancedSlider\Model\ResourceModel;
class Slides extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    protected function _construct()
    {
        $this->_init('absolute_advancedslider_slides','slide_id');
    }
}
