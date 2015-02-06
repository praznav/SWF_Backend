<?php

class Users_LoginAccountLockedView extends ShoppingwithfriendsUsersBaseView
{
	public function executeHtml(AgaviRequestDataHolder $rd)
	{
		$this->setupHtml($rd);
		
		$this->setAttribute('_title', 'Login');
	}

    public function executeXml(AgaviRequestDataHolder $rd)
    {
        // Create the xml response saying that the account is locked
        $xml = new DomDocument('1.0', 'utf-8');
        $xml->formatOutput = true;
        $root = $xml->createElement("response");
        $xml->appendChild($root);
        $status = $xml->createElement("status", "locked");
        $message = $xml->createElement("message", "The account you attempted to log in to is currently locked. Please contact an administrator in order to unlock it.");
        $root->appendChild($status);
        $root->appendChild($message);

        // Set the status code to 200 and return the xml response
        $this->getResponse()->setHttpStatusCode('200');
        $this->getResponse()->setContent($xml->saveXML());
    }

}

?>
