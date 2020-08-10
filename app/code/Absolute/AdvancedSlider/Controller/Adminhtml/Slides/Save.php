<?php
namespace Absolute\AdvancedSlider\Controller\Adminhtml\Slides;

use Absolute\AdvancedSlider\Model\Slides;
use Absolute\AdvancedSlider\Model\SlidesRepository;
use Magento\Backend\App\Action;
use Absolute\AdvancedSlider\Model\Page;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Exception\LocalizedException;
            
class Save extends \Magento\Backend\App\Action
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'Absolute_AdvancedSlider::slides';

    /**
     * @var DataPersistorInterface
     */
    protected $dataPersistor;
    /**
     * @var SlidesRepository
     */
    private $slidesRepository;
    /**
     * @var Slides
     */
    private $slides;

    /**
     * @param Action\Context $context
     * @param DataPersistorInterface $dataPersistor
     */
    public function __construct(
        Action\Context $context,
        DataPersistorInterface $dataPersistor,
        SlidesRepository $slidesRepository,
        Slides $slides
    ) {
        $this->dataPersistor = $dataPersistor;
        $this->slidesRepository = $slidesRepository;
        parent::__construct($context);
        $this->slides = $slides;
    }

    /**
     * Save action
     *
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $data = $this->getRequest()->getPostValue();
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($data) {

            if (empty($data['slide_id'])) {
                $data['slide_id'] = null;
            }

            $model = $this->slides;

            $id = $this->getRequest()->getParam('slide_id');
            if ($id) {
                /** @var \Absolute\AdvancedSlider\Model\Slides $model */
                $model = $this->slidesRepository->getById($id);
            }

            $data = $this->setImageData($data);
            $model->setData($data);

            try {
                $model->save();
                $this->messageManager->addSuccess(__('You saved the slide.'));
                $this->dataPersistor->clear('absolute_advancedslider_slides');
                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', ['slide_id' => $model->getId(), '_current' => true]);
                }
                return $resultRedirect->setPath('*/slides/index', ['slider_id' => $model->getSliderId()]);
            } catch (LocalizedException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addException($e, __('Something went wrong while saving the data. ' . $e->getMessage()));
            }

            $this->dataPersistor->set('absolute_advancedslider_slides', $data);
            return $resultRedirect->setPath('*/*/edit', ['slide_id' => $this->getRequest()->getParam('slide_id')]);
        }
        return $resultRedirect->setPath('*/*/');
    }

    /**
     * @param array $data
     * @return array
     */
    private function setImageData(array $data)
    {
        $images = [
            'image',
            'mobile_image'
        ];

        foreach($images as $image) {
            if (isset($data[$image][0]['name'])) {
                $data[$image] = $data[$image][0]['name'];
            } else {
                $data[$image] = null;
            }
        }

        return $data;
    }

}
