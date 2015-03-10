<?php

class Sales_RegisterSaleInvalidProductNameView extends ShoppingwithfriendsSalesBaseView
{
	public function executeHtml(AgaviRequestDataHolder $rd)
	{
		$this->setupHtml($rd);
		
		$this->setAttribute('_title', 'RegisterInterest');
	}

    public function executeXml(AgaviRequestDataHolder $rd)
    {
        // Create the xml response saying that the product name the user entered is invalid
        $xml = new DomDocument('1.0', 'utf-8');
        $xml->fomatOutput = true;
        $root = $xml->createElement("response");
        $xml->appendChild($root);
        $status = $xml->createElement("status", "invalidProductName");
        $message = $xml->createElement("message", "The product name you entered is invalid.");

        $root->appendChild($status);
        $root->appendChild($message);

        // Return the xml response
        $this->getResponse()->setContent($xml->saveXml());
    }

}

?>
