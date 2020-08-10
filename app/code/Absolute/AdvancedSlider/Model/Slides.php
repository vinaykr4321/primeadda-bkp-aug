<?php
namespace Absolute\AdvancedSlider\Model;
class Slides extends \Magento\Framework\Model\AbstractModel implements \Absolute\AdvancedSlider\Api\Data\SlidesInterface, \Magento\Framework\DataObject\IdentityInterface
{
    const CACHE_TAG = 'absolute_advancedslider_slides';

    protected $_eventPrefix = 'absolute_sliders';

    public $properties = [
        'title',
        'content',
        'image',
        'image_position',
        'background_color',
        'order',
        'active_from',
        'active_to',
    ];

    protected function _construct()
    {
        $this->_init('Absolute\AdvancedSlider\Model\ResourceModel\Slides');
    }

    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }
}
