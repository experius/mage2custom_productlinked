<?php
namespace Jeff\CustomLinked\Ui\DataProvider\Product\Related;

class CustomlinkedDataProvider extends \Magento\Catalog\Ui\DataProvider\Product\Related\AbstractDataProvider
{
    protected function getLinkType()
    {
        return 'clustomlinked';
    }
}
