<?php
namespace Absolute\AdvancedSlider\Model\Config\Source;

use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\App\RequestInterface;

class SlideSliderId implements \Magento\Framework\Data\ValueSourceInterface
{

    /**
     * @var DataPersistorInterface
     */
    private $dataPersistor;
    /**
     * @var Context
     */
    private $context;

    public function __construct(RequestInterface $request,
                                DataPersistorInterface $dataPersistor
    )
    {
        $this->request = $request;
        $this->dataPersistor = $dataPersistor;
    }

    public function getValue($name)
    {
        $data = $this->dataPersistor->get('absolute_advancedslider_slides');
        if (empty($data) && $id = $this->request->getParam('slider_id')) {
            return $id;
        }
        return $data['name'];
    }
}