<?php

class Products_UpdateSuccessView extends ShoppingwithfriendsProductsBaseView
{
	public function executeHtml(AgaviRequestDataHolder $rd)
	{
		$this->setupHtml($rd);
		
		$this->setAttribute('_title', 'Update');
	}
}

?>