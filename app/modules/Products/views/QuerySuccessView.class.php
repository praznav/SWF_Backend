<?php

class Products_QuerySuccessView extends ShoppingwithfriendsProductsBaseView
{
	public function executeHtml(AgaviRequestDataHolder $rd)
	{
		$this->setupHtml($rd);
		
		$this->setAttribute('_title', 'Query');
	}
}

?>