<?php
namespace Absolute\AdvancedSlider\Model;
use Absolute\AdvancedSlider\Model\ResourceModel\Slides\Collection;

class Sliders extends \Magento\Framework\Model\AbstractModel implements \Absolute\AdvancedSlider\Api\Data\SlidersInterface, \Magento\Framework\DataObject\IdentityInterface
{

    protected $_eventPrefix = 'absolute_sliders';

    const CACHE_TAG = 'absolute_advancedslider_sliders';

    /**
     * @var Collection
     */
    private $slides;

    /**
     * @var array
     */
    private $configurationProperties = [
        'slider_id',
        'name',
        'alias',
        'delay',
        'autoplay',
        'transition',
        'pagination',
        'arrows',
        'retina_image_size',
        'retina_mobile_image_size'
    ];

    /**
     * @var array
     */
    private $jsonProperties = [
        'name',
        'delay',
        'autoplay',
        'transition',
        'pagination',
        'arrows'
    ];

    protected function _construct()
    {
        $this->_init('Absolute\AdvancedSlider\Model\ResourceModel\Sliders');
    }

    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }

    /**
     * @return Collection
     */
    public function getSlides()
    {
        return $this->slides;
    }

    /**
     * @param Collection
     */
    public function setSlides($slides)
    {
        $this->slides = $slides;
        return $this;
    }

    /**
     * @return int
     */
    public function getPaginationCount()
    {
        $count = 0;
        foreach($this->slides as $slide) {
            if ($slide->getPaginationContent()) {
                $count++;
            }
        }
        return $count;
    }

    /**
     * @return array
     */
    public function getConfigurationProperties()
    {
        return $this->configurationProperties;
    }

    /**
     * @return array
     */
    public function getJSONConfiguration()
    {
        $config = [];
        foreach($this->getData() as $key => $value)
        {
            if (in_array($key, $this->jsonProperties)) {
                if (is_numeric($value)) {
                    $config[$key] = (int) $value;
                } else {
                    $config[$key] = $value;
                }
            }
        }
        return json_encode($config);
    }
}
