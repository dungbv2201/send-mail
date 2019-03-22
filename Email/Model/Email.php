<?php

namespace Dung\Email\Model;

use Dung\Email\Block\Email\ListProduct;
use Magento\Framework\App\Area;
use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\ResourceModel\AbstractResource;
use Magento\Framework\ObjectManagerInterface;
use Magento\Framework\Registry;
use Magento\Framework\Model\Context;
use Magento\Framework\View\Layout;
use Magento\Store\Model\App\Emulation;
use Magento\Store\Model\StoreManagerInterface;

class Email extends AbstractModel
{
    protected $transportBuilder;
    protected $storeManager;
    protected $blogBlock;
    protected $areaCode;
    protected $layout;
    protected $appEmulation;
    protected $_appState;
    protected $obj;

    public function __construct(Context $context,
                                Registry $registry,
                                Layout $layout,
                                Emulation $emulation,
                                StoreManagerInterface $storeManager,
                                TransportBuilder $transportBuilder,
                                ObjectManagerInterface $objectManager,
                                AbstractResource $resource = null,
                                \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null, array $data = [])
    {
        $this->storeManager     = $storeManager;
        $this->_appState        = $context->getAppState();
        $this->layout           = $layout;
        $this->transportBuilder = $transportBuilder;
        $this->appEmulation     = $emulation;
        $this->obj              = $objectManager;
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }

    public function send()
    {

        $receiverInfo = [
            'name'  => 'Dung',
            'email' => 'dungbv2201@gmail.com'
        ];

        $sender       = array('email' => "quyen949x2@gmail.com", 'name' => 'Quyen');
        $storeId      = $this->storeManager->getStore()->getId();
        $productBlock = $this->getBlockProduct();
        $this->appEmulation->startEnvironmentEmulation($storeId);
        $products = $this->_appState->emulateAreaCode(
            Area::AREA_FRONTEND,
            [$productBlock, 'toHtml']
        );

        $templateParams = ['store'              => $storeId,
                           'administrator_name' => $receiverInfo['name'],
                           'products'           => $products
        ];
        $this->appEmulation->stopEnvironmentEmulation();


        $transport = $this->transportBuilder->setTemplateIdentifier(
            'dung_email_demo_email_template'
        )->setTemplateOptions(
            ['area' => 'frontend', 'store' => $storeId]
        )->addTo(
            $receiverInfo['email'], $receiverInfo['name']
        )->setTemplateVars(
            $templateParams
        )->setFrom(
            $sender
        )->getTransport();

        $writer = new \Zend\Log\Writer\Stream(BP . '/var/log/cron.log');
        $logger = new \Zend\Log\Logger();
        $logger->addWriter($writer);
        $a = ($transport->getMessage()->getBody()->generateMessage());
        try {
            $transport->sendMessage();
        } catch (\Exception $e) {
            $logger->info('error');
        }
    }

    public function createBlockProduct($block)
    {
        if (is_string($block)) {
            if (class_exists($block)) {
                $block = $this->layout->createBlock($block);
            }
        }
        if (!$block instanceof \Magento\Framework\View\Element\AbstractBlock) {
            throw new \Magento\Framework\Exception\LocalizedException(__('Invalid block type: %1', $block));
        }
        return $block;
    }

    public function getBlockProduct()
    {
        if ($this->blogBlock === null) {
            $this->blogBlock = $this->createBlockProduct(ListProduct::class);
        }
        return $this->blogBlock;
    }


}