<?php

class Wishlist_ViewProductSuccessView extends ShoppingwithfriendsWishlistBaseView
{
	public function executeHtml(AgaviRequestDataHolder $rd)
	{
		$this->setupHtml($rd);
		
		$this->setAttribute('_title', 'ViewProduct');
	}
}

?>