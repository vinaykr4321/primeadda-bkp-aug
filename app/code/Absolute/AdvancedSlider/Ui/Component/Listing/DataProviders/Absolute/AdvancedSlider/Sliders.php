<?php
namespace Absolute\AdvancedSlider\Ui\Component\Listing\DataProviders\Absolute\AdvancedSlider;

class Sliders extends \Magento\Ui\DataProvider\AbstractDataProvider
{    
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        \Absolute\AdvancedSlider\Model\ResourceModel\Sliders\CollectionFactory $collectionFactory,
        array $meta = [],
        array $data = []
    ) {
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
        $this->collection = $collectionFactory->create();
    }
}
