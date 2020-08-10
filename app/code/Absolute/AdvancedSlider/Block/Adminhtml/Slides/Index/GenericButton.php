<?php
namespace Absolute\AdvancedSlider\Block\Adminhtml\Slides\Index;

use Magento\Framework\App\Request\DataPersistor;

class GenericButton
{

    public function __construct(
        \Magento\Backend\Block\Widget\Context $context,
        DataPersistor $dataPersistor
    ) {
        $this->context = $context;
    }

    public function getUrl($route = '', $params = [])
    {
        return $this->context->getUrlBuilder()->getUrl($route, $params);
    }

    public function getBackUrl()
    {
        return $this->getUrl('*/sliders/index');
    }
    
    public function getObjectId()
    {
        return $this->context->getRequest()->getParam('slider_id');
    }     
}
