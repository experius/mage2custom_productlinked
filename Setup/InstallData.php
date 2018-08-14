<?php
namespace Jeff\CustomLinked\Setup;

class InstallData implements \Magento\Framework\Setup\InstallDataInterface
{
    public function install(\Magento\Framework\Setup\ModuleDataSetupInterface $setup, \Magento\Framework\Setup\ModuleContextInterface $context)
    {
        $data = [
            [
                'link_type_id' => \Jeff\CustomLinked\Model\Product\Link::LINK_TYPE_CUSTOMLINKED,
                'code' =>  \Jeff\CustomLinked\Model\Product\Link::LINK_TYPE_CODE_CUSTOMLINKED
            ],
        ];

        foreach ($data as $bind) {
            $setup->getConnection()->insertForce($setup->getTable('catalog_product_link_type'), $bind);
        }

        $data = [
            [
                'link_type_id' => \Jeff\CustomLinked\Model\Product\Link::LINK_TYPE_CUSTOMLINKED,
                'product_link_attribute_code' => 'position',
                'data_type' => 'int',
            ]
        ];

        $setup->getConnection()->insertMultiple($setup->getTable('catalog_product_link_attribute'), $data);
    }
}
