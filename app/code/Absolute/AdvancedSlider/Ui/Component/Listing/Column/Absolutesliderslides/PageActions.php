<?php
namespace Absolute\AdvancedSlider\Ui\Component\Listing\Column\Absolutesliderslides;

class PageActions extends \Magento\Ui\Component\Listing\Columns\Column
{
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource["data"]["items"])) {
            foreach ($dataSource["data"]["items"] as & $item) {
                $name = $this->getData("name");
                $id = "X";
                if(isset($item["slide_id"]))
                {
                    $id = $item["slide_id"];
                }
                $item[$name]["view"] = [
                    "href"=>$this->getContext()->getUrl("absolute_advancedslider/slides/edit",["slide_id"=>$id]),
                    "label"=>__("Edit")
                ];

                $item[$name]["delete"] = [
                    "href" => $this->getContext()->getUrl("absolute_advancedslider/slides/delete",["slide_id"=>$id]),
                    "label" => __("Delete")
                ];
            }
        }

        return $dataSource;
    }    
    
}
