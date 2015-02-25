<?php

class Friends_RemoveInvalidUserView extends ShoppingwithfriendsFriendsBaseView
{
	public function executeHtml(AgaviRequestDataHolder $rd)
	{
		$this->setupHtml($rd);
		
		$this->setAttribute('_title', 'Remove');
	}

    public function executeXml(AgaviRequestDataHolder $rd)
    {
        // Create the xml response saying that the given user is invalid
        $xml = new DomDocument('1.0', 'utf-8');
        $xml->fomatOutput = true;
        $root = $xml->createElement("response");
        $xml->appendChild($root);
        $status = $xml->createElement("status", "invalidUser");
        $message = $xml->createElement("message", "The given user does not exist.");

        $root->appendChild($status);
        $root->appendChild($message);

        // Return the xml response
        $this->getResponse()->setContent($xml->saveXml());
    }

}

?>
