<?php
namespace Jeff\CustomLinked\Model\ProductLink\CollectionProvider;

class Customlinked implements \Magento\Catalog\Model\ProductLink\CollectionProviderInterface
{
    /**
     * {@inheritdoc}
     */
    public function getLinkedProducts(\Magento\Catalog\Model\Product $product)
    {
        return $product->getCustomlinkedProducts();
    }
}
