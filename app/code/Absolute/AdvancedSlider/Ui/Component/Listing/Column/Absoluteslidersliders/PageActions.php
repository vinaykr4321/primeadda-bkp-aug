<?php
namespace Absolute\AdvancedSlider\Ui\Component\Listing\Column\Absoluteslidersliders;

class PageActions extends \Magento\Ui\Component\Listing\Columns\Column
{
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource["data"]["items"])) {
            foreach ($dataSource["data"]["items"] as & $item) {
                $name = $this->getData("name");
                $id = "X";
                if(isset($item["slider_id"]))
                {
                    $id = $item["slider_id"];
                }

                $item[$name]["view"] = [
                    "href" => $this->getContext()->getUrl("absolute_advancedslider/sliders/edit",["slider_id"=>$id]),
                    "label" => __("Edit")
                ];

                $item[$name]["delete"] = [
                    "href" => $this->getContext()->getUrl("absolute_advancedslider/sliders/delete",["slider_id"=>$id]),
                    "label" => __("Delete")
                ];

                $item[$name]['view_slides'] = [
                    'href' => $this->getContext()->getUrl("absolute_advancedslider/slides/index",["slider_id"=>$id]),
                    'label' => __('View Slides')
                ];

                $item[$name]['add_slide'] = [
                    'href' => $this->getContext()->getUrl("absolute_advancedslider/slides/new",["slider_id"=>$id]),
                    'label' => __('Add Slide')
                ];


            }
        }

        return $dataSource;
    }    
    
}
