<?php

class Users_RegisterInvalidView extends ShoppingwithfriendsUsersBaseView
{
	public function executeHtml(AgaviRequestDataHolder $rd)
	{
		$this->setupHtml($rd);
		
		$this->setAttribute('_title', 'Register');
	}

    public function executeXml(AgaviRequestDataHolder $rd)
    {
        // Retrieve the errors variable so we can let the user know what is invalid
        $errors = $this->getAttribute('errors');

        // Create the xml response saying that there was a server error
        $xml = new DomDocument('1.0', 'utf-8');
        $xml->formatOutput = true;
        $root = $xml->createElement("response");
        $xml->appendChild($root);
        $status = $xml->createElement("status", "invalid");

        $root->appendChild($status);
        
        if (isset($errors['usernameErrors']))
        {
            $usernameErrorRoot = $xml->createElement("usernameErrors");
            foreach ($errors['usernameErrors'] as $usernameError)
            {
                $error = $xml->createElement("error", $usernameError);
                $usernameErrorRoot->appendChild($error);
            }
            $root->appendChild($usernameErrorRoot);
        }
        if (isset($errors['passwordErrors']))
        {
            $passwordErrorRoot = $xml->createElement("passwordErrors");
            foreach ($errors['passwordErrors'] as $passwordError)
            {
                $error = $xml->createElement("error", $passwordError);
                $passwordErrorRoot->appendChild($error);
            }
            $root->appendChild($passwordErrorRoot);
        }
        if (isset($errors['emailErrors']))
        {
            $emailErrorRoot = $xml->createElement("emailErrors");
            foreach ($errors['emailErrors'] as $emailError)
            {
                $error = $xml->createElement("error", $emailError);
                $emailErrorRoot->appendChild($error);
            }
            $root->appendChild($emailErrorRoot);
        }
        if (isset($errors['firstNameErrors']))
        {
            $firstNameErrorRoot = $xml->createElement("firstNameErrors");
            foreach ($errors['firstNameErrors'] as $firstNameError)
            {
                $error = $xml->createElement("error", $firstNameError);
                $firstNameErrorRoot->appendChild($error);
            }
            $root->appendChild($firstNameErrorRoot);
        }
        if (isset($errors['lastNameErrors']))
        {
            $lastNameErrorRoot = $xml->createElement("lastNameErrors");
            foreach ($errors['lastNameErrors'] as $lastNameError)
            {
                $error = $xml->createElement("error", $lastNameError);
                $lastNameErrorRoot->appendChild($error);
            }
            $root->appendChild($lastNameErrorRoot);
        }

        // Set the status code to 200 and return the xml response
        $this->getResponse()->setHttpStatusCode('200');
        $this->getResponse()->setContent($xml->saveXML());
    }

}

?>
