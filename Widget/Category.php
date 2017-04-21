<?php
namespace GaussDev\CategoryWidget\Widget;

use Magento\Framework\View\Element\Template;
use Magento\Catalog\Model\CategoryFactory;

class Category extends \Magento\Catalog\Block\Product\AbstractProduct implements \Magento\Widget\Block\BlockInterface
{
    protected $categoryFactory;

    public function __construct(
        \Magento\Catalog\Block\Product\Context $context,
        CategoryFactory $categoryFactory,
        array $data = [])
    {
        parent::__construct($context, $data);
        $this->categoryFactory = $categoryFactory;
    }


    public function getProductCollection(){

        if($this->getData('product_collection')){
           return $this->getData('product_collection');
        }
        if($this->getData('id_path')){
            /** @var  \Magento\Catalog\Model\ResourceModel\Product\Collection $product_collection */
            $category = $this->categoryFactory->create()
                ->load(filter_var($this->getData('id_path'), FILTER_SANITIZE_NUMBER_INT));

            $this->setProductCollection(
                $category->getProductCollection()
                    ->addAttributeToSort($this->getSortBy())
                    ->setPageSize($this->getItemsPerPage())
                    ->setOrder('position', \Magento\Framework\DB\Select::SQL_ASC)
            );
            return $this->getData('product_collection');
        }
        return false;
    }

    public function getTitle(){
        return $this->getData('title');
    }

    public function getItemsPerSlide(){
        return $this->getData('items_per_slide') ?: 4;
    }

    public function getItemsPerPage(){
        return $this->getData('items') ?: 10;
    }

    public function getSortBy(){
        return $this->getData('sortby') ?: 'position';
    }

     public function getShowCart(){
        return $this->getData('cart') ?: false;
    }
}