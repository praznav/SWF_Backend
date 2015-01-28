<?php

class Wishlist_AddProductSuccessView extends ShoppingwithfriendsWishlistBaseView
{
	public function executeHtml(AgaviRequestDataHolder $rd)
	{
		$this->setupHtml($rd);
		
		$this->setAttribute('_title', 'AddProduct');
	}
}

?>