<?php

class Users_UserManagerModel extends ShoppingwithfriendsUsersBaseModel
{
    /**
     * 0 - Login successful
     * 1 - Username or password incorrect
     * 2 - Accoutn locked
     */
    public function authenticate($username, $password)
    {
        // Make sure the username and password are set
        if (!isset($username) || !isset($password))
        {
            return 1;
        }

        // Try and find a user in the database with a matching username
        $user = UserQuery::create()->findOneByUsername($username);

        // Make sure that we actually found a user
        if (!isset($user))
        {
            return 1;
        }

        // Check to see if the account is locked
        if ($user->getIncorrectLoginAttempts() != null && $user->getIncorrectLoginAttempts() >= 3)
        {
            // More than 3 incorrect login attempts, the account is locked
            // Go ahead and check to see if the login was correct just so we can update the incorrect count
            if ($user->getPassword() != $password)
            {
                // The login was incorrect, increment the number of failed attempts and save that
                $user->setIncorrectLoginAttempts($user->getIncorrectLoginAttempts() + 1);
                $user->save();
            }
            return 2;
        }

        // Check to see if the login attempt is successful
        if ($user->getPassword() == $password)
        {
            // It is, so set the incorrect login attempts to 0 and the last successful login date to now
            $user->setIncorrectLoginAttempts(0);
            $user->setLastSuccessfulLogin(date("Y-m-d H:i:s"));
            $user->save();
            return 0;
        }

        // Otherwise, the account is not locked, but the login attempt failed
        // Increment the number of incorrect logins
        $user->setIncorrectLoginAttempts($user->getIncorrectLoginAttempts() + 1);
        $user->save();
        return 1;
    }
}

?>
