<?php

class Products_GetInfoSuccessView extends ShoppingwithfriendsProductsBaseView
{
	public function executeHtml(AgaviRequestDataHolder $rd)
	{
		$this->setupHtml($rd);
		
		$this->setAttribute('_title', 'GetInfo');
	}
}

?>