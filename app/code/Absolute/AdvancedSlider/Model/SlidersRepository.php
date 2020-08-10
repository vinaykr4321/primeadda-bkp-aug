<?php
namespace Absolute\AdvancedSlider\Model;

use Absolute\AdvancedSlider\Api\SlidersRepositoryInterface;
use Absolute\AdvancedSlider\Api\Data\SlidersInterface;
use Absolute\AdvancedSlider\Model\SlidersFactory;
use Absolute\AdvancedSlider\Model\ResourceModel\Sliders\CollectionFactory;

use Magento\Framework\Api\Search\FilterGroupBuilder;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Api\SearchResultsInterfaceFactory;
use Magento\Store\Model\StoreManagerInterface;

class SlidersRepository implements \Absolute\AdvancedSlider\Api\SlidersRepositoryInterface
{
    protected $objectFactory;
    protected $collectionFactory;
    /**
     * @var SearchCriteriaBuilder
     */
    private $searchCriteriaBuilder;
    /**
     * @var FilterGroupBuilder
     */
    private $filterGroupBuilder;
    /**
     * @var StoreManagerInterface
     */
    private $storeManager;
    /**
     * @var SlidesRepository
     */
    private $slidesRepository;

    public function __construct(
        SlidersFactory $objectFactory,
        CollectionFactory $collectionFactory,
        SearchResultsInterfaceFactory $searchResultsFactory,
        SearchCriteriaInterface $searchCriteriaBuilder,
        FilterGroupBuilder $filterGroupBuilder,
        StoreManagerInterface $storeManager,
        SlidesRepository $slidesRepository
    )
    {
        $this->objectFactory        = $objectFactory;
        $this->collectionFactory    = $collectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->filterGroupBuilder = $filterGroupBuilder;
        $this->storeManager = $storeManager;
        $this->slidesRepository = $slidesRepository;
    }
    
    public function save(SlidersInterface $object)
    {
        try
        {
            $object->save();
        }
        catch(\Exception $e)
        {
            throw new CouldNotSaveException(__($e->getMessage()));
        }
        return $object;
    }

    /**
     * @param $id
     * @return Sliders
     * @throws NoSuchEntityException
     */
    public function getById($id)
    {
        $object = $this->objectFactory->create();
        $object->load($id);
        if (!$object->getId()) {
            throw new NoSuchEntityException(__('Object with id "%1" does not exist.', $id));
        }
        return $object;        
    }

    /**
     * @param $id
     * @return SlidersRepository|bool
     * @throws NoSuchEntityException
     */
    public function getSliderById($id)
    {
        return $this->getSliderByField('slider_id', $id);
    }

    /**
     * @param $alias string
     * @return SlidersRepository|bool
     * @throws NoSuchEntityException
     */
    public function getSliderByAlias($alias)
    {
        return $this->getSliderByField('alias', $alias);
    }

    /**
     * @param $field string
     * @param $value string|int
     * @return $this|bool
     * @throws NoSuchEntityException
     */
    private function getSliderByField($field, $value)
    {
        $stores = [
            0, // all store views
            $this->storeManager->getStore()->getWebsiteId()
        ];

        /* $collection ResourceModel\Sliders\Collection */
        $collection = $this->collectionFactory->create();
        $collection->addFieldToFilter($field, ['eq' => $value])
            ->addFieldToFilter('main_table.store_id',['in' => $stores]);

        if ($collection->count() > 0) {
            /** @var $slider Sliders */
            $slider = $collection->getFirstItem();
            return $slider->setSlides($this->slidesRepository->getSlidesById($slider->getId()));
        }

        return false;
    }

    /**
     * @param SlidersInterface $object
     * @return bool
     * @throws CouldNotDeleteException
     */
    public function delete(SlidersInterface $object)
    {
        try {
            $object->delete();
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__($exception->getMessage()));
        }
        return true;    
    }

    /**
     * @param $id
     * @return bool
     * @throws CouldNotDeleteException
     * @throws NoSuchEntityException
     */
    public function deleteById($id)
    {
        return $this->delete($this->getById($id));
    }

    /**
     * @param SearchCriteriaInterface $criteria
     * @return \Magento\Framework\Api\SearchResultsInterface
     */
    public function getList(SearchCriteriaInterface $criteria)
    {
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($criteria);  
        $collection = $this->collectionFactory->create();
        foreach ($criteria->getFilterGroups() as $filterGroup) {
            $fields = [];
            $conditions = [];
            foreach ($filterGroup->getFilters() as $filter) {
                $condition = $filter->getConditionType() ? $filter->getConditionType() : 'eq';
                $fields[] = $filter->getField();
                $conditions[] = [$condition => $filter->getValue()];
            }
            if ($fields) {
                $collection->addFieldToFilter($fields, $conditions);
            }
        }  
        $searchResults->setTotalCount($collection->getSize());
        $sortOrders = $criteria->getSortOrders();
        if ($sortOrders) {
            /** @var SortOrder $sortOrder */
            foreach ($sortOrders as $sortOrder) {
                $collection->addOrder(
                    $sortOrder->getField(),
                    ($sortOrder->getDirection() == SortOrder::SORT_ASC) ? 'ASC' : 'DESC'
                );
            }
        }
        $collection->setCurPage($criteria->getCurrentPage());
        $collection->setPageSize($criteria->getPageSize());
        $objects = [];                                     
        foreach ($collection as $objectModel) {
            $objects[] = $objectModel;
        }
        $searchResults->setItems($objects);
        return $searchResults;        
    }
}
