<?php
namespace Absolute\AdvancedSlider\Model\Config\Source;

class ImagePosition implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        return [['value' => 'full', 'label' => __('Full')], ['value' => 'left', 'label' => __('Left')], ['value' => 'right', 'label' => __('Right')]];
    }

    /**
     * Get options in "key-value" format
     *
     * @return array
     */
    public function toArray()
    {
        return ['full' => __('Full'), 'left' => __('Left'), 'right' => __('Right')];
    }
}