<?php

class Sales_RegisterInterestAction extends ShoppingwithfriendsSalesBaseAction
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
            $productPrice = $rd->getParameter('maxPrice');
            $salesManager = $this->getContext()->getModel('SalesManager', 'Sales');
            if (!isset($salesManager))
            {
                return 'Error';
            }

            $interestRegisterStatus = $salesManager->registerInterest($username, $productName, $productPrice);
            if ($interestRegisterStatus == 0)
            {
                return 'Success';
            }
            else if ($interestRegisterStatus == 1)
            {
                return 'Error';
            }
            else if ($interestRegisterStatus == 2)
            {
                return 'InvalidUser';
            }
            else if ($interestRegisterStatus == 3)
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
        else if ($report->byArgument('maxPrice')->has())
        {
            return 'InvalidPrice';
        }
        else
        {
            return 'Error';
        }
    }

}

?>
