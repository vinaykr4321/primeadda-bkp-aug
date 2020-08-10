<?php
namespace Absolute\AdvancedSlider\Controller\Adminhtml\Sliders;

use Absolute\AdvancedSlider\Model\SlidersRepository;
use Magento\Backend\App\Action;

class Delete extends \Magento\Backend\App\Action
{  
    const ADMIN_RESOURCE = 'Absolute_AdvancedSlider::sliders';
    /**
     * @var SlidersRepository
     */
    private $slidersRepository;

    public function __construct(Action\Context $context,
                                SlidersRepository $slidersRepository
    )
    {
        $this->slidersRepository = $slidersRepository;
        parent::__construct($context);
    }

    public function execute()
    {
        // check if we know what should be deleted
        $id = $this->getRequest()->getParam('slider_id');
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($id) {
            $title = "";
            try {
                // init model and delete
                $this->slidersRepository->deleteById($id);
                // display success message
                $this->messageManager->addSuccess(__('You have deleted the slider.'));
                // go to grid
                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $e) {
                // display error message
                $this->messageManager->addError($e->getMessage());
                // go back to edit form
                return $resultRedirect->setPath('*/*/edit', ['slider_id' => $id]);
            }
        }
        // display error message
        $this->messageManager->addError(__('We can not find an slider to delete.'));
        // go to grid
        return $resultRedirect->setPath('*/*/');
        
    }    
    
}
