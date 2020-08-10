<?php
namespace Absolute\AdvancedSlider\Controller\Adminhtml\Slides;

use Magento\Framework\App\Request\DataPersistorInterface;

class Index extends \Magento\Backend\App\Action
{
    
    const ADMIN_RESOURCE = 'slides';
        
    protected $resultPageFactory;
    /**
     * @var DataPersistorInterface
     */
    private $dataPersistor;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        DataPersistorInterface $dataPersistor
    )
    {
        $this->resultPageFactory = $resultPageFactory;
        parent::__construct($context);


        $this->dataPersistor = $dataPersistor;
    }
    
    public function execute()
    {
        $this->dataPersistor->set('slider_id', $this->getRequest()->getParam('slider_id'));
        $this->_view->getPage()->getConfig()->getTitle()->prepend(__('View Slides - Absolute Slider'));
        return $this->resultPageFactory->create();  
    }
}
