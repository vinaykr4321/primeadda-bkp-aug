<?php
namespace Absolute\AdvancedSlider\Model\Slides;
use Absolute\AdvancedSlider\Model\ResourceModel\Slides\CollectionFactory;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Store\Model\StoreManagerInterface;

class DataProvider extends \Magento\Ui\DataProvider\AbstractDataProvider
{

    protected $collection;

    /**
     * @var DataPersistorInterface
     */
    protected $dataPersistor;

    /**
     * @var array
     */
    protected $loadedData;
    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param CollectionFactory $collectionFactory
     * @param DataPersistorInterface $dataPersistor
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $collectionFactory,
        DataPersistorInterface $dataPersistor,
        StoreManagerInterface $storeManager,
        array $meta = [],
        array $data = []
    ) {
        $this->collection = $collectionFactory->create();
        $this->dataPersistor = $dataPersistor;
        $this->meta = $this->prepareMeta($this->meta);
        $this->storeManager = $storeManager;
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }

    /**
     * Prepares Meta
     *
     * @param array $meta
     * @return array
     */
    public function prepareMeta(array $meta)
    {
        return $meta;
    }
    /**
     * Get data
     *
     * @return array
     */
    public function getData()
    {
        if (isset($this->loadedData)) {
            return $this->loadedData;
        }
        $items = $this->collection->getItems();

        foreach ($items as $item) {
            $this->loadedData[$item->getId()] = $item->getData();
            $imageData = [];

            if ($item->getImage()) {
                $imageData['image'][0]['name'] = $item->getImage();
                $imageData['image'][0]['url'] = $this->getMediaUrl().$item->getImage();
            }

            if ($item->getMobileImage()) {
                $imageData['mobile_image'][0]['name'] = $item->getMobileImage();
                $imageData['mobile_image'][0]['url'] = $this->getMediaUrl().$item->getMobileImage();
            }

            $fullData = $this->loadedData;
            $this->loadedData[$item->getId()] = array_merge($fullData[$item->getId()], $imageData);
        }

        $data = $this->dataPersistor->get('absolute_advancedslider_slides');
        if (!empty($data)) {
            $item = $this->collection->getNewEmptyItem();
            $item->setData($data);
            $this->loadedData[$item->getId()] = $item->getData();
            $this->dataPersistor->clear('absolute_advancedslider_slides');
        }

        return $this->loadedData;
    }

    public function getMediaUrl()
    {
        $mediaUrl = $this->storeManager->getStore()
                ->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA).'absolute_advancedslider/image/';
        return $mediaUrl;
    }
}
