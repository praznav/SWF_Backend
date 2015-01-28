<?php

class Wishlist_QuerySuccessView extends ShoppingwithfriendsWishlistBaseView
{
	public function executeHtml(AgaviRequestDataHolder $rd)
	{
		$this->setupHtml($rd);
		
		$this->setAttribute('_title', 'Query');
	}
}

?>