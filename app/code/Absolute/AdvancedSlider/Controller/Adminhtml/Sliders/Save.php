<?php
namespace Absolute\AdvancedSlider\Controller\Adminhtml\Sliders;

use Absolute\AdvancedSlider\Model\Sliders;
use Absolute\AdvancedSlider\Model\SlidersRepository;
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
    const ADMIN_RESOURCE = 'Absolute_AdvancedSlider::sliders';

    /**
     * @var DataPersistorInterface
     */
    protected $dataPersistor;
    /**
     * @var SlidersRepository
     */
    private $slidersRepository;
    /**
     * @var Sliders
     */
    private $sliders;

    /**
     * @param Action\Context $context
     * @param DataPersistorInterface $dataPersistor
     */
    public function __construct(
        Action\Context $context,
        DataPersistorInterface $dataPersistor,
        SlidersRepository $slidersRepository,
        Sliders $sliders
    ) {
        $this->dataPersistor = $dataPersistor;
        $this->slidersRepository = $slidersRepository;
        parent::__construct($context);
        $this->sliders = $sliders;
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
            if (isset($data['is_active']) && $data['is_active'] === 'true') {
                $data['is_active'] = Absolute\AdvancedSlider\Model\Sliders::STATUS_ENABLED;
            }
            if (empty($data['slider_id'])) {
                $data['slider_id'] = null;
            }

            $model = $this->sliders;

            $id = $this->getRequest()->getParam('slider_id');
            if ($id) {
                /** @var \Absolute\AdvancedSlider\Model\Sliders $model */
                $model = $this->slidersRepository->getById($id);
            }

            $model->setData($data);

            try {
                $model->save();
                $this->messageManager->addSuccess(__('You saved the thing.'));
                $this->dataPersistor->clear('absolute_advancedslider_sliders');
                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', ['slider_id' => $model->getId(), '_current' => true]);
                }
                return $resultRedirect->setPath('*/*/');
            } catch (LocalizedException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addException($e, __('Something went wrong while saving the data.'));
            }

            $this->dataPersistor->set('absolute_advancedslider_sliders', $data);
            return $resultRedirect->setPath('*/*/edit', ['slider_id' => $this->getRequest()->getParam('slider_id')]);
        }
        return $resultRedirect->setPath('*/*/');
    }    
}
