<?php
/**
 * Created by PhpStorm.
 * User: tomsaunders
 * Date: 10/10/2018
 * Time: 15:54
 */

namespace Absolute\AdvancedSlider\Block;

use Absolute\AdvancedSlider\Helper\Image;
use \Absolute\AdvancedSlider\Model\Sliders;
use Absolute\AdvancedSlider\Model\SlidersRepository;
use Magento\Customer\Model\SessionFactory;
use Magento\Framework\Filesystem\DirectoryList;
use Magento\Framework\View\Element\Template\Context;
use Magento\Framework\Exception\NoSuchEntityException;

class Slider extends \Magento\Framework\View\Element\Template implements \Magento\Widget\Block\BlockInterface, \Magento\Framework\DataObject\IdentityInterface
{

    protected $_template = 'slider.phtml';

    /**
     * @var Context
     */
    private $context;
    /**
     * @var Sliders
     */
    private $slider = false;
    /**
     * @var DirectoryList
     */
    private $directoryList;
    /**
     * @var Image
     */
    private $imageHelper;
    /**
     * @var SlidersRepository
     */
    private $slidersRepository;

    /**
     * Slider constructor.
     * @param Context $context
     * @param SliderHelper $sliderHelper
     * @param DirectoryList $directoryList
     * @param array $data
     */
    public function __construct(
        Context $context,
        DirectoryList $directoryList,
        Image $imageHelper,
        SlidersRepository $slidersRepository,
        array $data = []
    ) {
        $this->_scopeConfig = $context->getScopeConfig();
        $this->context = $context;
        $this->directoryList = $directoryList;
        $this->imageHelper = $imageHelper;
        $this->slidersRepository = $slidersRepository;
        parent::__construct($context, $data);
    }

    public function _construct()
    {
        $this->getSlider();
        parent::_construct();
    }

    /**
     * @return Sliders
     */
    public function getSlider()
    {
        if ($this->slider) {
            // if we already have a slider object, return it
            return $this->slider;
        }
        // check for slider id/alias and return it
        if ($this->getSliderAlias()) {
            $this->getSliderByAlias();
        }
        if ($this->getSliderId()) {
            $this->getSliderById();
        }

        return $this->slider;
    }

    /**
     * @return $this|Sliders|bool
     */
    public function getSliderById()
    {
        $id = $this->getSliderId();
        try {
            $this->slider = $this->slidersRepository->getSliderById($id);
        } catch (NoSuchEntityException $e) {
            // continue
        }
        return $this->slider;
    }

    /**
     * @return $this|Sliders|bool
     */
    public function getSliderByAlias()
    {
        $alias = $this->getSliderAlias();
        try {
            $this->slider = $this->slidersRepository->getSliderByAlias($alias);
        } catch (NoSuchEntityException $e) {
            // continue
        }
        return $this->slider;
    }

    /**
     * @param $image
     * @return string
     */
    public function getImageUrl($image)
    {
        $containerWidth = $this->slider->getData('retina_image_size');
        return $this->imageHelper->resize($image, $containerWidth);
    }

    /**
     * @param $image
     * @return string
     */
    public function getRetinaImageUrl($image)
    {
        $containerWidth = $this->slider->getData('retina_image_size');
        return $this->imageHelper->resize($image, $containerWidth*2);
    }

    /**
     * @param $image
     * @return string
     */
    public function getMobileImageUrl($image)
    {
        $containerWidth = $this->slider->getData('retina_mobile_image_size');
        return $this->imageHelper->resize($image, $containerWidth);
    }

    /**
     * @param $image
     * @return string
     */
    public function getMobileRetinaImageUrl($image)
    {
        $containerWidth = $this->slider->getData('retina_mobile_image_size');
        return $this->imageHelper->resize($image, $containerWidth*2);
    }

    /**
     * @return array
     */
    public function getIdentities()
    {
        return [\Absolute\AdvancedSlider\Model\Sliders::CACHE_TAG . '_' . $this->getSliderId()];
    }
}