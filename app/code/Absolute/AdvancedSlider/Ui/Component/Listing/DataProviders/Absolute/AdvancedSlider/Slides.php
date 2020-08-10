<?php
namespace Absolute\AdvancedSlider\Ui\Component\Listing\DataProviders\Absolute\AdvancedSlider;

use Magento\Framework\Api\FilterBuilder;
use Magento\Framework\App\RequestInterface;

class Slides extends \Magento\Ui\DataProvider\AbstractDataProvider
{
    /**
     * @var RequestInterface
     */
    private $request;
    /**
     * @var FilterBuilder
     */
    private $filterBuilder;

    /**
     * Slides constructor.
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param \Absolute\AdvancedSlider\Model\ResourceModel\Slides\CollectionFactory $collectionFactory
     * @param RequestInterface $request
     * @param FilterBuilder $filterBuilder
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        string $name,
        string $primaryFieldName,
        string $requestFieldName,
        \Absolute\AdvancedSlider\Model\ResourceModel\Slides\CollectionFactory $collectionFactory,
        RequestInterface $request,
        FilterBuilder $filterBuilder,
        array $meta = [],
        array $data = []
    ) {
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
        $this->collection = $collectionFactory->create();
        $this->request = $request;
        $this->filterBuilder = $filterBuilder;
        $this->prepareUpdateUrl();
    }

    /**
     * @return void
     */
    protected function prepareUpdateUrl()
    {
        if (!isset($this->data['config']['filter_url_params'])) {
            return;
        }
        foreach ($this->data['config']['filter_url_params'] as $paramName => $paramValue) {
            if ('*' == $paramValue) {
                $paramValue = $this->request->getParam($paramName);
            }
            if ($paramValue) {
                $this->data['config']['update_url'] = sprintf(
                    '%s%s/%s/',
                    $this->data['config']['update_url'],
                    $paramName,
                    $paramValue
                );
                $this->addFilter(
                    $this->filterBuilder->setField($paramName)->setValue($paramValue)->setConditionType('eq')->create()
                );
            }
        }
    }

}