<?php
/**
 * Created by PhpStorm.
 * User: vandung
 * Date: 12/03/2019
 * Time: 11:45
 */

namespace Dung\Email\Block\Email;

use Magento\Framework\View\Element\Template;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;

class ListProduct extends Template
{
    protected $_template = "Dung_Email::email/list_product.phtml";
    protected $productCollection;
    protected $directoryList;

    public function __construct(
        Template\Context $context,
        CollectionFactory $blogCollectionFactory,
        array $data = [])
    {
        $this->productCollection = $blogCollectionFactory;
        parent::__construct($context, $data);
    }

    public function getProductCollection()
    {
        $collection = $this->productCollection->create();
        $collection->addAttributeToSelect(['name','description']);
        $collection->setPageSize(3);
        return $collection;
    }
}