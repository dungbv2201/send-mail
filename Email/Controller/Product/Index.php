<?php
/**
 * Created by PhpStorm.
 * User: vandung
 * Date: 12/03/2019
 * Time: 11:07
 */

namespace Dung\Email\Controller\Product;


use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;

class Index extends Action
{
    protected $pageFactory;

    public function __construct(
        Context $context,
        PageFactory $pageFactory
    )
    {
        $this->pageFactory = $pageFactory;
        parent::__construct($context);
    }

    public function execute()
    {
        return $this->pageFactory->create();
    }
}