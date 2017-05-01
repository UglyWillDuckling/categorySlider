<?php
namespace GaussDev\CategoryWidget\Widget;

use Magento\Catalog\Model\CategoryFactory;



class Category extends \Magento\Catalog\Block\Product\AbstractProduct implements \Magento\Widget\Block\BlockInterface
{
    protected $categoryFactory;

    /**
     * Category constructor.
     * @param \Magento\Catalog\Block\Product\Context $context
     * @param CategoryFactory $categoryFactory
     * @param array $data
     */
    public function __construct(
        \Magento\Catalog\Block\Product\Context $context,
        CategoryFactory $categoryFactory,
        array $data = [])
    {
        parent::__construct($context, $data);
        $this->categoryFactory = $categoryFactory;
    }

    /**
     * @return bool|mixed
     */
    public function getProductCollection()
    {
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

    /**
     * @return int
     */
    public function getItemsPerSlide(){
        return $this->getData('items_per_slide') ?: 4;
    }

    /**
     * @return int
     */
    public function getItemsPerPage(){
        return $this->getData('items') ?: 10;
    }
}