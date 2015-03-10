<?php

class Sales_UsernameValidator extends AgaviValidator
{
    protected function validate()
    {
        $username = $this->getData($this->getArgument());
        if (!isset($username))
        {
            $this->throwError('not_set');
            return false;
        }

        if (strlen($username) < 4)
        {
            $this->throwError('too_short');
            return false;
        }

        if (strlen($username) > 25)
        {
            $this->throwError('too_long');
            return false;
        }

        if (preg_match('/[^A-Za-z0-9.\\-$]/', $username))
        {
            $this->throwError('invalid_character');
            return false;
        }

        error_log("Username is good...");

        $this->export($username);
        return true;
    }
}

?>
