<?php

class Users_UsernameValidator extends AgaviValidator
{
    protected function validate()
    {
        $username = $this->getData('username');
        if (!isset($username))
        {
            return false;
        }
        if (strlen($username) < 4 || strlen($username) > 25)
        {
            return false;
        }
        if (preg_match('/[^A-Za-z0-9.\\-$]/', $username))
        {
            return false;
        }
        $this->export($username);
        return true;
    }
}

?>
