<?php

class Sales_RegisterSaleAlreadyExistsView extends ShoppingwithfriendsSalesBaseView
{
	public function executeHtml(AgaviRequestDataHolder $rd)
	{
		$this->setupHtml($rd);
		
		$this->setAttribute('_title', 'RegisterInterest');
	}

    public function executeXml(AgaviRequestDataHolder $rd)
    {
        // Create the xml response saying that the user has already registered a sale for this product
        $xml = new DomDocument('1.0', 'utf-8');
        $xml->fomatOutput = true;
        $root = $xml->createElement("response");
        $xml->appendChild($root);
        $status = $xml->createElement("status", "alreadyExists");
        $message = $xml->createElement("message", "You are have already registered a sale for this product at the specified location.");

        $root->appendChild($status);
        $root->appendChild($message);

        // Return the xml response
        $this->getResponse()->setContent($xml->saveXml());
    }

}

?>
