<?php
/**
 * Created by PhpStorm.
 * User: hosein
 * Date: 2019-04-01
 * Time: 11:28
 */

namespace Netzexpert\OrderApproval\Controller\Approval;

use Magento\Framework\App\Action\Context;

class Confirm extends \Magento\Framework\App\Action\Action
{
    /**
     * @var \Magento\Quote\Model\QuoteRepository
     */
    private $quoteRepository;

    /**
     * @var \Magento\Checkout\Model\Session
     */
    private $session;

    /**
     * @var \Magento\Framework\App\RequestInterface
     */
    private $request;

    /**
     * @var \Magento\Framework\Message\ManagerInterface
     */
    protected $messageManager;

    /**
     * @var \Magento\Framework\Controller\Result\RedirectFactory
     */
    private $redirectFactory;

    /**
     * @var \Netzexpert\OrderApproval\Model\SendUserConfirmation
     */
    private $sendUserConfirmation;

    /**
     * Confirm constructor.
     * @param \Magento\Checkout\Model\Session $session
     * @param \Magento\Quote\Model\QuoteRepository $quoteRepository
     * @param \Magento\Framework\App\RequestInterface $request
     * @param \Magento\Framework\Message\ManagerInterface $messageManager
     * @param \Magento\Framework\Controller\Result\RedirectFactory $redirectFactory
     * @param Context $context
     */
    public function __construct(
        \Magento\Checkout\Model\Session $session,
        \Magento\Quote\Model\QuoteRepository $quoteRepository,
        \Magento\Framework\App\RequestInterface $request,
        \Magento\Framework\Message\ManagerInterface $messageManager,
        \Magento\Framework\Controller\Result\RedirectFactory $redirectFactory,
        \Netzexpert\OrderApproval\Model\SendUserConfirmation $sendUserConfirmation,
        Context $context
    )
    {
        $this->session = $session;
        $this->quoteRepository = $quoteRepository;
        $this->request = $request;
        $this->messageManager = $messageManager;
        $this->redirectFactory = $redirectFactory;
        $this->sendUserConfirmation = $sendUserConfirmation;
        parent::__construct($context);
    }

    public function execute()
    {
        $resultRedirect = $this->redirectFactory->create();
        $param = $this->request->getParam('id');
        try {
            $quote = $this->quoteRepository->get($param);
        } catch (\Exception $exception) {
            $this->messageManager->getMessages();
        }
        if ($quote->getData('is_order') == null) {
            $quote->setData('is_order', '1');
            $this->quoteRepository->save($quote);
            try {
                $this->sendUserConfirmation->execute();
            } catch (\Exception $exception) {
                $this->messageManager->getMessages();
            }
            $this->messageManager->addSuccessMessage('You have successfully granted user permissions for proceeding checkout!');
            $resultRedirect->setPath('/');
            return $resultRedirect;
        }
        $this->messageManager->addErrorMessage('User already has permissions for proceeding checkout.');
        $resultRedirect->setPath('/');
        return $resultRedirect;
    }
}