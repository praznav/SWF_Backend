<?php

class Friends_ListSuccessView extends ShoppingwithfriendsFriendsBaseView
{
	public function executeHtml(AgaviRequestDataHolder $rd)
	{
		$this->setupHtml($rd);
		
		$this->setAttribute('_title', 'List');
	}

    public function executeXml(AgaviRequestDataHolder $rd)
    {
        // Create the xml response with the users list of friends
        $xml = new DomDocument('1.0', 'utf-8');
        $xml->formatOutput = true;
        $root = $xml->createElement("response");
        $xml->appendChild($root);
        $status = $xml->createElement("status", "success");
        $friends = $xml->createElement("friends");

        // Loop through all the friends and add them
        foreach ($this->getAttribute('friends') as $friend)
        {
            $friendXml = $xml->createElement('friend', $friend);
            $friends->appendChild($friendXml);
        }

        $root->appendChild($status);
        $root->appendChild($friends);

        // Return the xml response
        $this->getResponse()->setContent($xml->saveXml());
    }
}

?>
