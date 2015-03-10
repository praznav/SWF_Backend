<?php

class Sales_RegisterInterestNotAuthorizedView extends ShoppingwithfriendsSalesBaseView
{
	public function executeHtml(AgaviRequestDataHolder $rd)
	{
		$this->setupHtml($rd);
		
		$this->setAttribute('_title', 'RegisterInterest');
	}

    public function executeXml(AgaviRequestDataHolder $rd)
    {
        // Create the xml response saying that the user is not authorized to perform this action
        $xml = new DomDocument('1.0', 'utf-8');
        $xml->fomatOutput = true;
        $root = $xml->createElement("response");
        $xml->appendChild($root);
        $status = $xml->createElement("status", "notAuthorized");
        $message = $xml->createElement("message", "You are not authorized to perform this action. Please check your username and password, then try again.");

        $root->appendChild($status);
        $root->appendChild($message);

        // Return the xml response
        $this->getResponse()->setContent($xml->saveXml());
    }

}

?>
