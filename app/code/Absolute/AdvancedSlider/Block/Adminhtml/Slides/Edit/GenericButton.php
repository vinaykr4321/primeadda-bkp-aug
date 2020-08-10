<?php
namespace Absolute\AdvancedSlider\Block\Adminhtml\Slides\Edit;

use Magento\Framework\App\Request\DataPersistor;

class GenericButton
{

    public function __construct(
        \Magento\Backend\Block\Widget\Context $context,
        DataPersistor $dataPersistor
    ) {
        $this->context = $context;
    }
    
    public function getBackUrl()
    {
        return $this->getUrl('*/sliders/index');
    }    
    
    public function getDeleteUrl()
    {
        return $this->getUrl('*/*/delete', ['slide_id' => $this->getObjectId()]);
    }   
    
    public function getUrl($route = '', $params = [])
    {
        return $this->context->getUrlBuilder()->getUrl($route, $params);
    }    
    
    public function getObjectId()
    {
        return $this->context->getRequest()->getParam('slide_id');
    }     
}
