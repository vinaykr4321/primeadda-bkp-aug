<?php
namespace Absolute\AdvancedSlider\Block\Adminhtml\Sliders\Edit;
use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;
class ViewSlidesButton extends GenericButton implements ButtonProviderInterface
{     
    public function getButtonData()
    {
        
        return [
            'label' => __('View Slides'),
            'on_click' => sprintf("location.href = '%s';", $this->getUrl("absolute_advancedslider/slides/index",["slider_id"=>$this->getObjectId()])),
            'class' => '',
            'sort_order' => 10    
        ];
    }
}
