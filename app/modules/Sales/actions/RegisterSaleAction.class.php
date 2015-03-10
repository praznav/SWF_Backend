<?php

class Sales_RegisterSaleAction extends ShoppingwithfriendsSalesBaseAction
{
	/**
	 * Returns the default view if the action does not serve the request
	 * method used.
	 *
	 * @return     mixed <ul>
	 *                     <li>A string containing the view name associated
	 *                     with this action; or</li>
	 *                     <li>An array with two indices: the parent module
	 *                     of the view to be executed and the view to be
	 *                     executed.</li>
	 *                   </ul>
	 */
	public function getDefaultViewName()
	{
		return 'Success';
	}

    public function executeWrite(AgaviRequestDataHolder $rd)
    {
        $username = $rd->getParameter('username');
        $password = $rd->getParameter('password');
        $userManager = $this->getContext()->getModel('UserManager', 'Users');
        if (!isset($userManager))
        {
            return 'Error';
        }
        $authenticationStatus = $userManager->authenticate($username, $password);
        if ($authenticationStatus == 0)
        {
            $productName = $rd->getParameter('productName');
            $productPrice = $rd->getParameter('productPrice');
            $productLocation = $rd->getParameter('productLocation');
            $salesManager = $this->getContext()->getModel('SalesManager', 'Sales');
            if (!isset($salesManager))
            {
                return 'Error';
            }

            $saleRegisterStatus = $salesManager->registerSale($username, $productName, $productPrice, $productLocation);
            if ($saleRegisterStatus == 0)
            {
                return 'Success';
            }
            else if ($saleRegisterStatus == 1)
            {
                return 'Error';
            }
            else if ($saleRegisterStatus == 2)
            {
                return 'InvalidUser';
            }
            else if ($saleRegisterStatus == 3)
            {
                return 'AlreadyExists';
            }
            else
            {
                return 'Error';
            }
        }
        else if ($authenticationStatus == 1)
        {
            return 'NotAuthorized';
        }
        else
        {
            return 'Error';
        }
    }

    public function handleWriteError(AgaviRequestDataHolder $rd)
    {
        // Check for validation errors and send the corresponding view
        $report = $this->getContainer()->getValidationManager()->getReport();
        if ($report->byArgument('username')->has())
        {
            return 'NotAuthorized';
        }
        else if ($report->byArgument('password')->has())
        {
            return 'NotAuthorized';
        }
        else if ($report->byArgument('productName')->has())
        {
            return 'InvalidProductName';
        }
        else if ($report->byArgument('productPrice')->has())
        {
            return 'InvalidPrice';
        }
        else if ($report->byArgument('productLocation')->has())
        {
            return 'InvalidLocation';
        }
        else
        {
            return 'Error';
        }
    }

}

?>
