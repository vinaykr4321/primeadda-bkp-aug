<?php
namespace Absolute\AdvancedSlider\Block\Adminhtml\Slides\Index;
use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

class EditSliderButton extends GenericButton implements ButtonProviderInterface
{     
    public function getButtonData()
    {
        return [
            'label' => __('Edit Slider'),
            'on_click' => sprintf("location.href = '%s';", $this->getUrl("absolute_advancedslider/sliders/edit",["slider_id"=>$this->getObjectId()])),
            'class' => '',
            'sort_order' => 10    
        ];
    }
}
