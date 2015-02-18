<?php

class Users_EmailValidator extends AgaviValidator
{
    protected function validate()
    {
        $email = $this->getData($this->getArgument());
        if (!isset($email))
        {
            error_log("Email is not set in validator");
            $this->throwError('not_set');
            return false;
        }

        if (strlen($email) < 5)
        {
            error_log("Email length is too short, length is: " . strlen($email));
            $this->throwError('too_short');
            return false;
        }

        if (strlen($email) > 50)
        {
            error_log("Email length is too long, length is: " . strlen($email));
            $this->throwError('too_long');
            return false;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL))
        {
            error_log("Email is not a valid email, email is: " . $email);
            $this->throwError('not_valid');
            return false;
        }

        error_log("Email successfully validated in email validator");
        $this->export($email);
        return true;
    }
}

?>
