<?php

class Sales_PasswordValidator extends AgaviValidator
{
    protected function validate()
    {
        $password = $this->getData($this->getArgument());
        if (!isset($password))
        {
            $this->throwError('not_set');
            return false;
        }

        error_log("Password is good...");

        $this->export($password);
        return true;
    }
}

?>
