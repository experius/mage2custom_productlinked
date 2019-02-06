<?php
namespace Jeff\CustomLinked\Model\ProductLink\CollectionProvider;

class Customlinked implements \Magento\Catalog\Model\ProductLink\CollectionProviderInterface
{

    protected $customLinkedProductModel;

    public function __construct(
        \Jeff\CustomLinked\Model\Product $customLinkedProductModel
    ) {
        $this->customLinkedProductModel = $customLinkedProductModel;
    }

    /**
     * {@inheritdoc}
     */
    public function getLinkedProducts(\Magento\Catalog\Model\Product $product)
    {
        $this->customLinkedProductModel->setProduct($product)->getCustomlinkedProducts();
        return $product->getCustomlinkedProducts();
    }
}
