<?php

class Sales_ProductNameValidator extends AgaviValidator
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

        if (strlen($name) > 50)
        {
            $this->throwError('too_long');
            return false;
        }

        if (preg_match('/[^A-Za-z0-9.\\-\\s\']/', $name))
        {
            $this->throwError('invalid_character');
            return false;
        }

        error_log("Product name is good...");

        $this->export($name);
        return true;
    }
}

?>
