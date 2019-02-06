<?php
namespace Jeff\CustomLinked\Model;

/**
 * Class \Magento\Catalog\Model\ProductLink\CollectionProvider\Related
 *
 */
class Product
{

    protected $product;

    /**
     * Retrieve array of related products
     *
     * @return array
     */
    public function getCustomlinkedProducts()
    {
        if (!$this->getProduct()->hasCustomlinkedProducts()) {
            $products = [];
            $collection = $this->getCustomlinkedProductCollection($this->getProduct());
            foreach ($collection as $product) {
                $products[] = $product;
            }
            $this->getProduct()->setCustomlinkedProducts($products);
        }
        return $this->getProduct();
    }
    /**
     * Retrieve related products identifiers
     *
     * @return array
     */
    public function getCustomlinkedProductIds()
    {
        if (!$this->getProduct()->hasCustomlinkedProductIds()) {
            $ids = [];
            foreach ($this->getCustomlinkedProducts() as $product) {
                $ids[] = $product->getId();
            }
            $this->getProduct()->setCustomlinkedProductIds($ids);
        }
        return [$this->getProduct()->getData('customlinked_product_ids')];
    }
    /**
     * Retrieve collection related product
     *
     * @return \Magento\Catalog\Model\ResourceModel\Product\Link\Product\Collection
     */
    public function getCustomlinkedProductCollection($product)
    {
        $collection = $product->getLinkInstance()
            ->setLinkTypeId(\Jeff\CustomLinked\Model\Product\Link::LINK_TYPE_CUSTOMLINKED)
            ->getProductCollection()
            ->setIsStrongMode();
        $collection->setProduct($product);

        return $collection;
    }

    /**
     * @return mixed
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * @param mixed $product
     */
    public function setProduct($product)
    {
        $this->product = $product;

        return $this;
    }
}
