<?php

class Friends_PasswordValidator extends AgaviValidator
{
    protected function validate()
    {
        $password = $this->getData($this->getArgument());
        if (!isset($password))
        {
            $this->throwError('not_set');
            return false;
        }

        $this->export($password);
        return true;
    }
}

?>
