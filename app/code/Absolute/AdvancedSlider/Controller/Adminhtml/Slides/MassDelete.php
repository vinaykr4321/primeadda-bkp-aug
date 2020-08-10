<?php
namespace Absolute\AdvancedSlider\Controller\Adminhtml\Slides;

use Absolute\AdvancedSlider\Model\ResourceModel\Slides\CollectionFactory;
use Absolute\AdvancedSlider\Model\SlidesRepository;
use Magento\Backend\App\Action;
use Magento\Framework\App\Request\DataPersistorInterface;

class MassDelete extends \Magento\Backend\App\Action
{
    const ADMIN_RESOURCE = 'Absolute_AdvancedSlider::slides';
    /**
     * @var \Magento\Ui\Component\MassAction\Filter
     */
    private $filter;
    /**
     * @var CollectionFactory
     */
    private $collectionFactory;
    /**
     * @var DataPersistorInterface
     */
    private $dataPersistor;

    public function __construct(Action\Context $context,
                                \Magento\Ui\Component\MassAction\Filter $filter,
                                CollectionFactory $collectionFactory,
                                DataPersistorInterface $dataPersistor
    )
    {
        $this->filter = $filter;
        $this->collectionFactory = $collectionFactory;
        $this->dataPersistor = $dataPersistor;
        parent::__construct($context);
    }

    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        $collection = $this->filter->getCollection($this->collectionFactory->create());

        // get the current selected slider
        $sliderId = $this->dataPersistor->get('slider_id');
        if (!$sliderId) {
            $this->messageManager->addErrorMessage(__('No Slider Id found...'));
            // go back to edit form
            return $resultRedirect->setPath('*/*/index');
        }

        // filter the existing collection by slider id
        $collection->addFieldToFilter('slider_id', $sliderId);

        // delete the slides
        try {
            foreach ($collection->getItems() as $slide) {
                $slide->delete();
            }
            $this->messageManager->addSuccess(
                __('A total of %1 record(s) have been deleted.', $collection->getSize())
            );

        } catch (\Exception $e) {
            // display error message
            $this->messageManager->addError(__($e->getMessage()));
            // go back to grid
            return $resultRedirect->setPath('*/*/index', ['slider_id' => $sliderId]);
        }

        // go go back to grid
        return $resultRedirect->setPath('*/*/index', ['slider_id' => $sliderId]);

    }

}
