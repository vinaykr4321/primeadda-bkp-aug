<?php
namespace Absolute\AdvancedSlider\Model\Config\Source;

use Absolute\AdvancedSlider\Model\SlidersRepository;
use Magento\Framework\Api\SearchCriteriaInterface;

class SlidersList implements \Magento\Framework\Option\ArrayInterface
{

    /**
     * @var SlidersRepository
     */
    private $slidersRepository;

    /**
     * @var SearchCriteriaInterface
     */
    private $searchCriteria;

    private $sliders;

    public function __construct(SlidersRepository $slidersRepository, SearchCriteriaInterface $searchCriteria)
    {
        $this->slidersRepository = $slidersRepository;
        $this->searchCriteria = $searchCriteria;
        $this->sliders = $this->slidersRepository->getList($this->searchCriteria)->getItems();
    }

    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        $list = [];
        if ($this->sliders) {
            foreach($this->sliders as $slide) {
                $list[] = [
                    'value' => $slide->getAlias(),
                    'label' => $slide->getName()
                ];
            }
        }
        return $list;
    }

    /**
     * Get options in "key-value" format
     *
     * @return array
     */
    public function toArray()
    {
        $list = [];
        if ($this->sliders) {
            foreach($this->sliders as $slide) {
                $list[] = [$slide->getAlias() => $slide->getTitle()];
            }
        }
        return $list;
    }
}