<?php

class Friends_RemoveErrorView extends ShoppingwithfriendsFriendsBaseView
{
	public function executeHtml(AgaviRequestDataHolder $rd)
	{
		$this->setupHtml($rd);
		
		$this->setAttribute('_title', 'Remove');
	}

    public function executeXml(AgaviRequestDataHolder $rd)
    {
        // Create the xml response saying that there was a server error
        $xml = new DomDocument('1.0', 'utf-8');
        $xml->fomatOutput = true;
        $root = $xml->createElement("response");
        $xml->appendChild($root);
        $status = $xml->createElement("status", "error");
        $message = $xml->createElement("message", "A server error ocurred. Please try again later.");

        $root->appendChild($status);
        $root->appendChild($message);

        // Set the status code to 500 to indicate an error and return the xml response
        $this->getResponse()->setHttpStatusCode('500');
        $this->getResponse()->setContent($xml->saveXml());
    }

}

?>
