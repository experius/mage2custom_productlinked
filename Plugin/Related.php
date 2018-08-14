<?php
namespace Jeff\CustomLinked\Plugin;

use Magento\Catalog\Ui\DataProvider\Product\Form\Modifier\Related as RelatedParent;
use Magento\Ui\Component\Form\Fieldset;

class Related extends RelatedParent
{
    const GROUP_RELATED = 'related';

    private $priceModifier;

    protected $product;

    public function afterModifyMeta($modify, $result)
    {
        if (isset($result[static::GROUP_RELATED]['children'])) {
            $result[static::GROUP_RELATED]['children'][$modify->scopePrefix . \Jeff\CustomLinked\Model\Product\Link::LINK_TYPE_CODE_CUSTOMLINKED] = $this->getCustomlinkedFieldset($modify);
        }
        return $result;
    }

    /**
     * Get price modifier
     *
     * @return \Magento\Catalog\Ui\Component\Listing\Columns\Price
     * @deprecated 101.0.0
     */

    private function getPriceModifier($modify)
    {
        if (!$this->priceModifier) {
            $this->priceModifier = \Magento\Framework\App\ObjectManager::getInstance()->get(
                \Magento\Catalog\Ui\Component\Listing\Columns\Price::class
            );
        }
        return $this->priceModifier;
    }

    /**
     * Prepares config for the Related products fieldset
     *
     * @return array
     * @since 101.0.0
     */
    protected function getCustomlinkedFieldset($modify)
    {
        $content = __(
            'My Custom Linked Product.'
        );
        return [
            'children' => [
                'button_set' => $modify->getButtonSet(
                    $content,
                    __('Add Custom Linked'),
                    $modify->scopePrefix . \Jeff\CustomLinked\Model\Product\Link::LINK_TYPE_CODE_CUSTOMLINKED
                ),
                'modal' => $this->getGenericModal(
                    __('Add Custom Linked'),
                    $modify->scopePrefix . \Jeff\CustomLinked\Model\Product\Link::LINK_TYPE_CODE_CUSTOMLINKED
                ),
                \Jeff\CustomLinked\Model\Product\Link::LINK_TYPE_CODE_CUSTOMLINKED => $this->getGrid($modify->scopePrefix . \Jeff\CustomLinked\Model\Product\Link::LINK_TYPE_CODE_CUSTOMLINKED),
            ],
            'arguments' => [
                'data' => [
                    'config' => [
                        'additionalClasses' => 'admin__fieldset-section',
                        'label' => __('Custom Linked'),
                        'collapsible' => false,
                        'componentType' => Fieldset::NAME,
                        'dataScope' => '',
                        'sortOrder' => 11,
                    ],
                ],
            ]
        ];
    }

    public function afterModifyData($modify, $data)
    {
        $product = $modify->locator->getProduct();
        $productId = $product->getId();
        if (!$productId) {
            return $data;
        }
        $priceModifier = $this->getPriceModifier($modify);
        /**
         * Set field name for modifier
         */
        $priceModifier->setData('name', 'price');
        $dataScopes = $this->getDataScopes();
        $dataScopes[] = \Jeff\CustomLinked\Model\Product\Link::LINK_TYPE_CODE_CUSTOMLINKED;
        foreach ($dataScopes as $dataScope) {
            if ($dataScope == \Jeff\CustomLinked\Model\Product\Link::LINK_TYPE_CODE_CUSTOMLINKED) {
                $data[$productId]['links'][$dataScope] = [];
                foreach ($modify->productLinkRepository->getList($product) as $linkItem) {
                    if ($linkItem->getLinkType() !== $dataScope) {
                        continue;
                    }
                    /** @var \Magento\Catalog\Model\Product $linkedProduct */
                    $linkedProduct = $modify->productRepository->get(
                        $linkItem->getLinkedProductSku(),
                        false,
                        $modify->locator->getStore()->getId()
                    );
                        $data[$productId]['links'][$dataScope][] = $this->fillData($linkedProduct, $linkItem);
                }
                if (!empty($data[$productId]['links'][$dataScope])) {
                    $dataMap = $priceModifier->prepareDataSource([
                    'data' => [
                        'items' => $data[$productId]['links'][$dataScope]
                    ]
                    ]);
                    $data[$productId]['links'][$dataScope] = $dataMap['data']['items'];
                }
            }
        }
        
        return $data;
    }

    public function beforeGetLinkedProducts($provider, $product)
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $this->product = $objectManager->create('Jeff\CustomLinked\Model\Product');
        $currentProduct = $this->product->load($product->getId());
        return [$currentProduct];
    }
}
