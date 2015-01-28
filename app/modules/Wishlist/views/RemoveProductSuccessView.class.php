<?php

class Wishlist_RemoveProductSuccessView extends ShoppingwithfriendsWishlistBaseView
{
	public function executeHtml(AgaviRequestDataHolder $rd)
	{
		$this->setupHtml($rd);
		
		$this->setAttribute('_title', 'RemoveProduct');
	}
}

?>