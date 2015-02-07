<?php

class Users_NameValidator extends AgaviValidator
{
    protected function validate()
    {
        $name = $this->getData($this->getArgument());
        if (!isset($name))
        {
            $this->throwError('not_set');
            return false;
        }

        if (strlen($name) < 1)
        {
            $this->throwError('too_short');
            return false;
        }

        if (strlen($name) > 25)
        {
            $this->throwError('too_long');
            return false;
        }

        if (preg_match('/[^A-Za-z.\\-\']/', $name))
        {
            $this->throwError('invalid_character');
            return false;
        }

        $this->export($name);
        return true;
    }
}

?>
