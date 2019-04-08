<?php
/**
 * Created by PhpStorm.
 * User: hosein
 * Date: 2019-03-26
 * Time: 12:21
 */

namespace Netzexpert\OrderApproval\Block\Checkout\Cart;


use Magento\Email\Model\Template\Config;

class ApprovalRequest extends \Magento\Framework\View\Element\Template
{
    /**
     * @var Config
     */
    private $emailConfig;

    /**
     * ApprovalRequest constructor.
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param Config $config
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        Config $config,
        array $data = []
    )
    {
        parent::__construct($context, $data);
        $this->emailConfig = $config;
    }

    /**
     * @return array|array[]
     */
    public function getAvailableTemplates() {
        $templates = $this->emailConfig->getAvailableTemplates();
        $module = 'Netzexpert_OrderApproval';
        return array_filter($templates, function ($var) use ($module) {
            return ($var['group'] == $module);
        });
    }

    public function getFormUrl()
    {
        return $this->getUrl('order/approval/send');
    }

}