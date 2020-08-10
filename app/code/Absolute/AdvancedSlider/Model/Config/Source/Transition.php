<?php
namespace Absolute\AdvancedSlider\Model\Config\Source;

class Transition implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        return [['value' => 'slide', 'label' => __('Slide')], ['value' => 'fade', 'label' => __('Fade')]];
    }

    /**
     * Get options in "key-value" format
     *
     * @return array
     */
    public function toArray()
    {
        return ['slide' => __('Slide'), 'fade' => __('Fade')];
    }
}
