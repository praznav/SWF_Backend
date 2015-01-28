<?php

class Friends_DenyInviteSuccessView extends ShoppingwithfriendsFriendsBaseView
{
	public function executeHtml(AgaviRequestDataHolder $rd)
	{
		$this->setupHtml($rd);
		
		$this->setAttribute('_title', 'DenyInvite');
	}
}

?>