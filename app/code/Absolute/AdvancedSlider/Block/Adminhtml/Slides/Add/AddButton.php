<?php
namespace Absolute\AdvancedSlider\Block\Adminhtml\Slides\Add;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

class AddButton implements ButtonProviderInterface
{
    public function __construct(
        \Magento\Backend\Block\Widget\Context $context
    ) {
        $this->context = $context;
    }

    public function getUrl($route = '', $params = [])
    {
        return $this->context->getUrlBuilder()->getUrl($route, $params);
    }

    public function getObjectId()
    {
        return $this->context->getRequest()->getParam('slider_id');
    }

    private function getAddSlideUrl()
    {
        return $this->getUrl('*/slides/new', ['slider_id' => $this->getObjectId()]);
    }

    public function getButtonData()
    {
        if(!$this->getObjectId()) { return []; }
        return [
            'label' => __('Add New Slide'),
            'class' => 'primary',
            'name' => 'add',
            'on_click' => sprintf("location.href = '%s';", $this->getAddSlideUrl()),
            'sort_order' => 10,
        ];
    }


}
