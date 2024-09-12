<?php
namespace LandingPage\Form\Block;

use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\View\Element\Template;
use Magento\Framework\ObjectManagerInterface;

class Index extends Template
{
    /**
     * @var CustomerSession
     */
    protected $customerSession;

    /**
     * Constructor.
     *
     * @param Template\Context $context
     * @param ObjectManagerInterface $objectManager
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        ObjectManagerInterface $objectManager,
        array $data = []
    ) {
        $this->customerSession = $objectManager->get(CustomerSession::class);
        parent::__construct($context, $data);
    }

    /**
     * Get the customer's first name.
     *
     * @return string
     */
    public function getCustomerName(): string
    {
        $customer = $this->customerSession->getCustomer();
        return $customer->getFirstname() ?: '';
    }

    public function getCustomerEmail(): string
    {
        $customer = $this->customerSession->getCustomer();
        return $customer->getEmail() ?: '';
    }
}