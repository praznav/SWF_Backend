<?php

class Users_LoginErrorView extends ShoppingwithfriendsUsersBaseView
{
	public function executeHtml(AgaviRequestDataHolder $rd)
	{
		$this->setupHtml($rd);
		
		$this->setAttribute('_title', 'Login');
	}

    public function executeXml(AgaviRequestDataHolder $rd)
    {
        // Create the xml response saying that there was a server error
        $xml = new DomDocument('1.0', 'utf-8');
        $xml->formatOutput = true;
        $root = $xml->createElement("response");
        $xml->appendChild($root);
        $status = $xml->createElement("status", "error");
        $message = $xml->createElement("message", "A server error occurred. Please try again later.");
        $root->appendChild($status);
        $root->appendChild($message);

        // Set the status code to 500 to indicate an error and return the xml response
        $this->getResponse()->setHttpStatusCode('500');
        $this->getResponse()->setContent($xml->saveXML());
    }
}

?>
