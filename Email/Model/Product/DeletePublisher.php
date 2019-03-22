<?php
/**
 * Created by PhpStorm.
 * User: vandung
 * Date: 07/03/2019
 * Time: 13:22
 */

namespace Dung\Email\Model\Product;


class DeletePublisher
{
    const TOPIC_NAME = 'dung.demo.product';

    /**
     * @var \Magento\Framework\MessageQueue\PublisherInterface
     */
    private $publisher;

    /**
     * @param \Magento\Framework\MessageQueue\PublisherInterface $publisher
     */
    public function __construct(\Magento\Framework\MessageQueue\PublisherInterface $publisher)
    {
        $this->publisher = $publisher;
    }

    /**
     * {@inheritdoc}
     */
    public function execute(\Magento\Catalog\Api\Data\ProductInterface $product)
    {
        $this->publisher->publish(self::TOPIC_NAME, $product);
    }
}