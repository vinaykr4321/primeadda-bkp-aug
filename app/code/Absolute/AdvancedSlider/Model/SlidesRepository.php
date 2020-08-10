<?php
namespace Absolute\AdvancedSlider\Model;

use Absolute\AdvancedSlider\Api\SlidesRepositoryInterface;
use Absolute\AdvancedSlider\Api\Data\SlidesInterface;
use Absolute\AdvancedSlider\Model\SlidesFactory;
use Absolute\AdvancedSlider\Model\ResourceModel\Slides\CollectionFactory;

use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Data\Collection;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Api\SearchResultsInterfaceFactory;
use Magento\Store\Model\StoreManagerInterface;

class SlidesRepository implements \Absolute\AdvancedSlider\Api\SlidesRepositoryInterface
{
    protected $objectFactory;
    protected $collectionFactory;
    /**
     * @var Sliders
     */
    private $sliderModel;
    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    public function __construct(
        SlidesFactory $objectFactory,
        CollectionFactory $collectionFactory,
        SearchResultsInterfaceFactory $searchResultsFactory,
        StoreManagerInterface $storeManager,
        Sliders $sliderModel
    )
    {
        $this->objectFactory        = $objectFactory;
        $this->collectionFactory    = $collectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->sliderModel = $sliderModel;
        $this->storeManager = $storeManager;
    }
    
    public function save(SlidesInterface $object)
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
     * @return ResourceModel\Slides\Collection|bool
     * @throws NoSuchEntityException
     */
    public function getSlidesById($id)
    {
        $stores = [
            0, // all store views
            $this->storeManager->getStore()->getWebsiteId()
        ];

        /* $collection ResourceModel\Slides\Collection */
        $collection = $this->collectionFactory->create();
        $collection->addFieldToFilter("main_table.slider_id", ['eq' => $id])
            ->addFieldToFilter('active_from', [['lteq' => date('Y-m-d H:i:s')], ['null' => true]])
            ->addFieldToFilter('active_to', [['gteq' => date('Y-m-d H:i:s')], ['null' => true]])
            ->addFieldToFilter('main_table.store_id',['in' => $stores])
            ->setOrder('main_table.order', Collection::SORT_ORDER_ASC);

        if ($collection->count() > 0) {
            return $collection;
        }

        return false;
    }

    public function delete(SlidesInterface $object)
    {
        try {
            $object->delete();
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__($exception->getMessage()));
        }
        return true;    
    }    

    public function deleteById($id)
    {
        return $this->delete($this->getById($id));
    }    

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
    }}
