<?php

class Users_EmailValidator extends AgaviValidator
{
    protected function validate()
    {
        $email = $this->getData($this->getArgument());
        if (!isset($email))
        {
            $this->throwError('not_set');
            return false;
        }

        if (strlen($email) < 5)
        {
            $this->throwError('too_short');
            return false;
        }

        if (strlen($email) > 50)
        {
            $this->throwError('too_long');
            return false;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL))
        {
            $this->throwError('invalid_character');
            return false;
        }

        $this->export($email);
        return true;
    }
}

?>
