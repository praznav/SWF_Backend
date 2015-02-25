<?php

class Friends_ListAction extends ShoppingwithfriendsFriendsBaseAction
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

    public function executeRead(AgaviRequestDataHolder $rd)
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
            $friendManager = $this->getContext()->getModel('FriendManager', 'Friends');
            if (!isset($friendManager))
            {
                return 'Error';
            }
            $friends = $friendManager->getList($username);
            $this->setAttribute('friends', $friends);
            return 'Success';
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
