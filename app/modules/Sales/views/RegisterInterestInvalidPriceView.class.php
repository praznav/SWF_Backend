<?php

class Sales_RegisterInterestInvalidPriceView extends ShoppingwithfriendsSalesBaseView
{
	public function executeHtml(AgaviRequestDataHolder $rd)
	{
		$this->setupHtml($rd);
		
		$this->setAttribute('_title', 'RegisterInterest');
	}

    public function executeXml(AgaviRequestDataHolder $rd)
    {
        // Create the xml response saying that the given price is invalid
        $xml = new DomDocument('1.0', 'utf-8');
        $xml->fomatOutput = true;
        $root = $xml->createElement("response");
        $xml->appendChild($root);
        $status = $xml->createElement("status", "invalidPrice");
        $message = $xml->createElement("message", "The price you have entered for your interest is invalid.");

        $root->appendChild($status);
        $root->appendChild($message);

        // Return the xml response
        $this->getResponse()->setContent($xml->saveXml());
    }

}

?>
