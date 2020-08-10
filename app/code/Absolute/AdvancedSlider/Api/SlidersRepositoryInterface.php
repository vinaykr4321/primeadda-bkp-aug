<?php
namespace Absolute\AdvancedSlider\Api;

use Absolute\AdvancedSlider\Api\Data\SlidersInterface;
use Magento\Framework\Api\SortOrder;
use Magento\Framework\Api\SearchCriteriaInterface;

interface SlidersRepositoryInterface 
{
    public function save(SlidersInterface $page);

    public function getById($id);

    public function getList(SearchCriteriaInterface $criteria);

    public function delete(SlidersInterface $page);

    public function deleteById($id);
}
