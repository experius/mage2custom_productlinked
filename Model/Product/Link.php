<?php
namespace Jeff\CustomLinked\Model\Product;

class Link extends \Magento\Catalog\Model\Product\Link
{
    const LINK_TYPE_CUSTOMLINKED = 17;

    const LINK_TYPE_CODE_CUSTOMLINKED = 'customlinked';
}
