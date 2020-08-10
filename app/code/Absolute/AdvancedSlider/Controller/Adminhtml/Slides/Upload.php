<?php
namespace Absolute\AdvancedSlider\Controller\Adminhtml\Slides;

use Absolute\AdvancedSlider\Api\SlidersRepositoryInterface;
use Absolute\AdvancedSlider\Model\ImageUploader;
use Absolute\AdvancedSlider\Model\Page;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Controller\ResultFactory;

class Upload extends \Magento\Backend\App\Action
{
    /**
     * @var ImageUploader
     */
    public $imageUploader;

    public function __construct(
        Context $context,
        ImageUploader $imageUploader
    ) {
        parent::__construct($context);
        $this->imageUploader = $imageUploader;
    }

    public function _isAllowed()
    {
        return $this->_authorization->isAllowed('Absolute_AdvancedSlider::slides');
    }

    public function execute()
    {
        $sliderId = $this->getRequest()->getParam('slider_id');
        try {
            $result = $this->imageUploader->saveFileToBaseDir($this->getRequest()->getParam('input'));
            $result['cookie'] = [
                'name' => $this->_getSession()->getName(),
                'value' => $this->_getSession()->getSessionId(),
                'lifetime' => $this->_getSession()->getCookieLifetime(),
                'path' => $this->_getSession()->getCookiePath(),
                'domain' => $this->_getSession()->getCookieDomain(),
            ];
        } catch (\Exception $e) {
            $result = ['error' => $e->getMessage(), 'errorcode' => $e->getCode()];
        }
        return $this->resultFactory->create(ResultFactory::TYPE_JSON)->setData($result);
    }
}
