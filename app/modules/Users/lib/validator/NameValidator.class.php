<?php

class Users_NameValidator extends AgaviValidator
{
    protected function validate()
    {
        error_log("Validating name of argument " . $this->getArgument());
        $name = $this->getData($this->getArgument());
        error_log("Name is: " . $name);
        if (!isset($name))
        {
            error_log("Name is not set in validator");
            $this->throwError('not_set');
            return false;
        }

        if (strlen($name) < 1)
        {
            error_log("Name is too short, length is: " . strlen($name));
            $this->throwError('too_short');
            return false;
        }

        if (strlen($name) > 25)
        {
            error_log("Name is too long, length is: " . strlen($name));
            $this->throwError('too_long');
            return false;
        }

        if (preg_match('/[^A-Za-z.\\-\']/', $name))
        {
            error_log("Name has invalid characters, name is: " . $name);
            $this->throwError('invalid_character');
            return false;
        }

        error_log("Name successfully validated in name validator");
        $this->export($name);
        return true;
    }
}

?>
