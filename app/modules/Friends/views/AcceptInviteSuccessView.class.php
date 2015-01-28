<?php

class Friends_AcceptInviteSuccessView extends ShoppingwithfriendsFriendsBaseView
{
	public function executeHtml(AgaviRequestDataHolder $rd)
	{
		$this->setupHtml($rd);
		
		$this->setAttribute('_title', 'AcceptInvite');
	}
}

?>