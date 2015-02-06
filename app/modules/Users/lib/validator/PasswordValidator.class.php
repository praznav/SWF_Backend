<?php

class Users_PasswordValidator extends AgaviValidator
{
    protected function validate()
    {
        $password = $this->getData('password');
        if (!isset($password))
        {
            return false;
        }
        $this->export($password);
        return true;
    }
}

?>
