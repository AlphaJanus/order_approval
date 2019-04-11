# order_approval_magento2
Magento 2 module that restricts checkout completing without approval.
On the Edit Cart page "Proceed to Checkout" button does not appear, instead there is "Request for Approval" button. 
It send cart to admin, which can confirm or decline user proceeding to checkout.
When order confirmed, user receives email, where he can follow link and open Edit Cart page with created before cart and proceed to checkout.(button "Proceed to Checkout" now will be available)
When order declined, user receives email with notification.
