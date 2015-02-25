<?php

class Friends_GetSuccessView extends ShoppingwithfriendsFriendsBaseView
{
	public function executeHtml(AgaviRequestDataHolder $rd)
	{
		$this->setupHtml($rd);
		
		$this->setAttribute('_title', 'Get');
	}

    public function executeXml(AgaviRequestDataHolder $rd)
    {
        // Create the xml response with the friend
        $xml = new DomDocument('1.0', 'utf-8');
        $xml->formatOutput = true;
        $root = $xml->createElement("response");
        $xml->appendChild($root);
        $status = $xml->createElement("satus", "success");
        $friend = $xml->createElement("friend");
        $friendUsername = $xml->createElement("friendUsername", $this->getAttribute("friend")->getUsername());
        $friend->appendChild($friendUsername);

        $friendFirstName = $xml->createElement("friendFirstName", $this->getAttribute("friend")->getFirstName());
        $friend->appendChild($friendFirstName);

        $friendLastName = $xml->createElement("friendLastName", $this->getAttribute("friend")->getLastName());
        $friend->appendChild($friendLastName);

        $friendEmail = $xml->createElement("friendEmail", $this->getAttribute("friend")->getEmail());
        $friend->appendChild($friendEmail);

        $friendRating = $xml->createElement("friendRating", $this->getAttribute("friend")->getRating());
        $friend->appendChild($friendRating);

        $friendReportCount = $xml->createElement("friendReportCount", $this->getAttribute("friend")->getReportCount());
        $friend->appendChild($friendReportCount);

        $root->appendChild($status);
        $root->appendChild($friend);

        // Return the xml response
        $this->getResponse()->setContent($xml->saveXml());
    }

}

?>
