<?php

class Friends_InviteFriendSuccessView extends ShoppingwithfriendsFriendsBaseView
{
	public function executeHtml(AgaviRequestDataHolder $rd)
	{
		$this->setupHtml($rd);
		
		$this->setAttribute('_title', 'InviteFriend');
	}
}

?>