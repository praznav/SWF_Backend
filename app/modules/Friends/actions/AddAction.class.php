<?php

class Friends_AddAction extends ShoppingwithfriendsFriendsBaseAction
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
        $friendUsername = $rd->getParameter('friendUsername');
        $userManager = $this->getContext()->getModel('UserManager', 'Users');
        if (!isset($userManager))
        {
            return 'Error';
        }
        $authenticationStatus = $userManager->authenticate($username, $password);
        if ($authenticationStatus == 0)
        {
            $friendManager = $this->getContext()->getModel('FriendManager', 'Friends');
            if (!isset($friendManager))
            {
                return 'Error';
            }
            $friendAddStatus = $friendManager->add($username, $friendUsername);
            if ($friendAddStatus == 0)
            {
                return 'Success';
            }
            else if ($friendAddStatus == 1)
            {
                return 'Error';
            }
            else if ($friendAddStatus == 2)
            {
                return 'InvalidUser';
            }
            else if ($friendAddStatus == 3)
            {
                return 'AlreadyFriends';
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
}

?>
