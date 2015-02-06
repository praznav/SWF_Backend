<?php

class Users_LoginIncorrectLoginView extends ShoppingwithfriendsUsersBaseView
{
	public function executeHtml(AgaviRequestDataHolder $rd)
	{
		$this->setupHtml($rd);
		
		$this->setAttribute('_title', 'Login');
	}

    public function executeXml(AgaviRequestDataHolder $rd)
    {
        // Create the xml response saying that there was an incorrect username or password
        $xml = new DomDocument('1.0', 'utf-8');
        $xml->formatOutput = true;
        $root = $xml->createElement("response");
        $xml->appendChild($root);
        $status = $xml->createElement("status", "incorrect");
        $message = $xml->createElement("message", "The given username or password is invalid.");
        $root->appendChild($status);
        $root->appendChild($message);

        // Set the status code to 200 and return the xml response
        $this->getResponse()->setHttpStatusCode('200');
        $this->getResponse()->setContent($xml->saveXML());
    }

}

?>
