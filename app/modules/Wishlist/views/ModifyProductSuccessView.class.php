<?php

class Wishlist_ModifyProductSuccessView extends ShoppingwithfriendsWishlistBaseView
{
	public function executeHtml(AgaviRequestDataHolder $rd)
	{
		$this->setupHtml($rd);
		
		$this->setAttribute('_title', 'ModifyProduct');
	}
}

?>