<?php
namespace Absolute\AdvancedSlider\Controller\Adminhtml\Slides;

use Absolute\AdvancedSlider\Model\SlidesRepository;
use Magento\Backend\App\Action;
use Magento\Framework\App\Request\DataPersistor;

class Delete extends \Magento\Backend\App\Action
{  
    const ADMIN_RESOURCE = 'Absolute_AdvancedSlider::slides';
    /**
     * @var SlidesRepository
     */
    private $slides;
    /**
     * @var SlidesRepository
     */
    private $slidesRepository;
    /**
     * @var DataPersistor
     */
    private $dataPersistor;


    public function __construct(Action\Context $context,
                                SlidesRepository $slidesRepository,
                                DataPersistor $dataPersistor
    )
    {
        $this->slidesRepository = $slidesRepository;
        $this->dataPersistor = $dataPersistor;
        parent::__construct($context);
    }

    public function execute()
    {
        // check if we know what should be deleted
        $id = $this->getRequest()->getParam('slide_id');
        $sliderId = $this->dataPersistor->get('slider_id');

        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($id) {
            $title = "";
            try {
                // init model and delete
                $this->slidesRepository->deleteById($id);
                // display success message
                $this->messageManager->addSuccess(__('You have deleted the slide.'));
                // go to grid
                return $resultRedirect->setPath('*/*/', ['slider_id' => $sliderId]);
            } catch (\Exception $e) {
                // display error message
                $this->messageManager->addError($e->getMessage());
                // go back to edit form
                return $resultRedirect->setPath('*/*/edit', ['slide_id' => $id]);
            }
        }
        // display error message
        $this->messageManager->addError(__('We can not find an object to delete.'));
        // go to grid
        return $resultRedirect->setPath('*/*/', ['slider_id' => $sliderId]);
        
    }    
    
}
