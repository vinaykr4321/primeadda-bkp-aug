<?php
namespace Absolute\AdvancedSlider\Controller\Adminhtml\Sliders;

class Edit extends \Magento\Backend\App\Action
{
    const ADMIN_RESOURCE = 'Absolute_AdvancedSlider::sliders';

    protected $resultPageFactory;
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory)
    {
        $this->resultPageFactory = $resultPageFactory;
        parent::__construct($context);
    }
    
    public function execute()
    {
        $this->_view->getPage()->getConfig()->getTitle()->prepend(__('Edit Slider - Absolute Slider'));
        return $this->resultPageFactory->create();  
    }    
}
