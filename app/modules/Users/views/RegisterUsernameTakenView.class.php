<?php

class Users_RegisterUsernameTakenView extends ShoppingwithfriendsUsersBaseView
{
	public function executeHtml(AgaviRequestDataHolder $rd)
	{
		$this->setupHtml($rd);
		
		$this->setAttribute('_title', 'Register');
	}

    public function executeXml(AgaviRequestDataHolder $rd)
    {
        // Create the xml response saying that the requested username is taken
        $xml = new DomDocument('1.0', 'utf-8');
        $xml->formatOutput = true;
        $root = $xml->createElement("response");
        $xml->appendChild($root);
        $status = $xml->createElement("status", "taken");
        $message = $xml->createElement("message", "The username you requested is currently taken. Please try a different one.");
        $root->appendChild($status);
        $root->appendChild($message);

        // Set the status code to 200 and return the xml response
        $this->getResponse()->setHttpStatusCode('200');
        $this->getResponse()->setContent($xml->saveXML());
    }

}

?>
