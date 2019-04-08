<?php
/**
 * Created by PhpStorm.
 * User: hosein
 * Date: 2019-03-26
 * Time: 16:04
 */

namespace Netzexpert\OrderApproval\Controller\Approval;

use Magento\Framework\App\Action\Context;

class Send extends \Magento\Framework\App\Action\Action
{
    /**
     * @var \Magento\Framework\App\Request\Http
     */
    private $request;

    /**
     * @var \Magento\Framework\Mail\Template\TransportBuilder
     */
    private $transportBuilder;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var \Magento\Framework\Controller\Result\RedirectFactory
     */
    protected $resultRedirectFactory;

    /**
     * @var \Magento\Checkout\Model\Session
     */
    private $session;

    /**
     * SendCartMyAccount constructor.
     * @param Context $context
     * @param \Magento\Framework\App\Request\Http $request
     * @param \Magento\Framework\Mail\Template\TransportBuilder $transportBuilder
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Framework\UrlInterface $urlInterface
     */
    public function __construct(
        Context $context,
        \Magento\Framework\App\Request\Http $request,
        \Magento\Framework\Mail\Template\TransportBuilder $transportBuilder,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\UrlInterface $urlInterface,
        \Magento\Checkout\Model\Session $session

    ) {
        $this->request = $request;
        $this->transportBuilder = $transportBuilder;
        $this->storeManager = $storeManager;
        $this->_url = $urlInterface;
        $this->session = $session;
        parent::__construct($context);
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface|void
     * @throws \Magento\Framework\Exception\MailException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function execute()
    {
        $store = $this->storeManager->getStore()->getId();
        $quote = $this->session->getQuote();
        $name = $quote->getCustomerFirstname();
        $quoteId = $quote->getData();
        $link = $this->_url->getUrl('order/approval/confirm', ['id' => $quoteId['entity_id']]);
        $link2 = $this->_url->getUrl('order/approval/decline', ['id' => $quoteId['entity_id']]);
        $transport = $this->transportBuilder->setTemplateIdentifier('request_email_template')
            ->setTemplateOptions(['area' => 'frontend', 'store' => $store])
            ->setTemplateVars(
                [
                    'store'        => $this->storeManager->getStore(),
                    'quote'        => $quote,
                    'link'         => $link,
                    'link2'        => $link2,
                    'name'         => $name
                ]
            )
            ->setFrom('general')
            ->addTo('info@leex.de')
            ->getTransport();
        $transport->sendMessage();
    }
}