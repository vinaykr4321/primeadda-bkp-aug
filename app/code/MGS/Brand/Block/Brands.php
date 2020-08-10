<?php

namespace MGS\Brand\Block;

use Magento\Customer\Model\Context as CustomerContext;

class Brands extends \Magento\Framework\View\Element\Template
{
    protected $_coreRegistry = null;
    protected $_brandHelper;
    protected $_brand;
    protected $_storeManager;
    protected $httpContext;
    protected $_catalogProductVisibility;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \MGS\Brand\Helper\Data $brandHelper,
        \MGS\Brand\Model\Brand $brand,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\App\Http\Context $httpContext,
        \Magento\Catalog\Model\Product\Visibility $catalogProductVisibility,
        array $data = []
    )
    {
        $this->_brand = $brand;
        $this->_coreRegistry = $registry;
        $this->_storeManager = $storeManager;
        $this->_brandHelper = $brandHelper;
        $this->httpContext = $httpContext;
        $this->_catalogProductVisibility = $catalogProductVisibility;
        parent::__construct($context, $data);
    }

    public function _construct()
    {
        if (!$this->getConfig('general_settings/enabled')) return;
        parent::_construct();
        $this->addData(
            ['cache_lifetime' => 86400, 'cache_tags' => [\Magento\Catalog\Model\Product::CACHE_TAG]]
        );
        $brand = $this->_brand;
        $brandCollection = $brand->getCollection()
            ->addFieldToFilter('status', 1)
            ->addStoreFilter($this->_storeManager->getStore()->getId())
            ->setOrder('sort_order', 'ASC');
        $params = $this->getRequest()->getParams();
        if (isset($params['keyword']) && $params['keyword'] != '') {
            $brandCollection->addFieldToFilter('name', ['like' => '%' . $params['keyword'] . '%']);
        }
        if (isset($params['char']) && $params['char'] != '' && $params['char'] != '0-9') {
            $brandCollection->addFieldToFilter('name', ['like' => $params['char'] . '%']);
        }
        $this->setCollection($brandCollection);
    }

    public function getCacheKeyInfo()
    {
        return [
            'SHOP_BY_BRAND',
            $this->_storeManager->getStore()->getId(),
            $this->_design->getDesignTheme()->getId(),
            $this->httpContext->getValue(CustomerContext::CONTEXT_GROUP),
            'template' => $this->getTemplate()
        ];
    }

    protected function _addBreadcrumbs()
    {
        $breadcrumbsBlock = $this->getLayout()->getBlock('breadcrumbs');
        $baseUrl = $this->_storeManager->getStore()->getBaseUrl();
        $pageTitle = $this->_brandHelper->getConfig('list_page_settings/title');
        $breadcrumbsBlock->addCrumb(
            'home',
            [
                'label' => __('Home'),
                'title' => __('Go to Home Page'),
                'link' => $baseUrl
            ]
        );
        $breadcrumbsBlock->addCrumb(
            'brand',
            [
                'label' => $pageTitle,
                'title' => $pageTitle,
                'link' => ''
            ]
        );
    }

    public function setCollection($collection)
    {
        $this->_collection = $collection;
        return $this->_collection;
    }

    public function getCollection()
    {
        return $this->_collection;
    }

    public function getConfig($key, $default = '')
    {
        $result = $this->_brandHelper->getConfig($key);
        if (!$result) {
            return $default;
        }
        return $result;
    }

    protected function _prepareLayout()
    {
        $pageTitle = $this->getConfig('list_page_settings/title');
        $metaKeywords = $this->getConfig('list_page_settings/meta_keywords');
        $metaDescription = $this->getConfig('list_page_settings/meta_description');
        $this->_addBreadcrumbs();
        $this->pageConfig->addBodyClass('brand-list');
        if ($pageTitle) {
            $this->pageConfig->getTitle()->set($pageTitle);
        }
        if ($metaKeywords) {
            $this->pageConfig->setKeywords($metaKeywords);
        }
        if ($metaDescription) {
            $this->pageConfig->setDescription($metaDescription);
        }
		
		$pager = $this->getLayout()->createBlock(
			'Magento\Theme\Block\Html\Pager',
			'brand.list.pager'
		);
		
		$limitConfig = $this->_brandHelper->getConfig('list_page_settings/per_page');
		if($limitConfig!=''){
			$limitConfig = explode(',',$limitConfig);
			$avaliableLimit = [];
			foreach($limitConfig as $value){
				$avaliableLimit[$value] = $value;
			}
			$pager->setAvailableLimit($avaliableLimit);
		}
		
		$pager->setCollection(
			$this->getCollection()
		);
		
		$this->setChild('pager', $pager);
		
        return parent::_prepareLayout();
    }
	
	public function getPagerHtml()
    {
        return $this->getChildHtml('pager');
    }

    public function getProductCount(\MGS\Brand\Model\Brand $brand)
    {
        $collection = $brand->getProductCollection();
        $collection->setVisibility($this->_catalogProductVisibility->getVisibleInCatalogIds());
        return count($collection);
    }

}