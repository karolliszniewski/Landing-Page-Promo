<?php
namespace LandingPage\Form\Block;

use Magento\Framework\View\Element\Template;

class Index extends Template{
    /**
     * Get catalog display text
     * 
     * @return string
     */
    public function getCatalogDisplayText(){
        return "Block content on index index page";
    }
}