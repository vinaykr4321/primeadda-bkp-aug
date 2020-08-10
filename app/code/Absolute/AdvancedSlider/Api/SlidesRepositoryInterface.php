<?php
namespace Absolute\AdvancedSlider\Api;

use Absolute\AdvancedSlider\Api\Data\SlidesInterface;
use Magento\Framework\Api\SortOrder;
use Magento\Framework\Api\SearchCriteriaInterface;

interface SlidesRepositoryInterface 
{
    public function save(SlidesInterface $page);

    public function getById($id);

    public function getList(SearchCriteriaInterface $criteria);

    public function delete(SlidesInterface $page);

    public function deleteById($id);
}
