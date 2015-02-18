<?php

class Users_RegisterAction extends ShoppingwithfriendsUsersBaseAction
{
	/**
	 * Returns the default view if the action does not serve the request
	 * method used.
	 *
	 * @return     mixed <ul>
	 *                     <li>A string containing the view name associated
	 *                     with this action; or</li>
	 *                     <li>An array with two indices: the parent module
	 *                     of the view to be executed and the view to be
	 *                     executed.</li>
	 *                   </ul>
	 */
	public function getDefaultViewName()
	{
		return 'Success';
	}

    public function executeWrite(AgaviRequestDataHolder $rd)
    {
        $username = $rd->getParameter('username');
        $password = $rd->getParameter('password');
        $email = $rd->getParameter('email');
        $firstName = $rd->getParameter('firstName');
        $lastName = $rd->getParameter('lastName');
        $userManager = $this->getContext()->getModel('UserManager', 'Users');
        if (!isset($userManager))
        {
            return 'Error';
        }

        $registrationStatus = $userManager->registerUser($username, $password, $email, $firstName, $lastName);
        if ($registrationStatus == 0)
        {
            return 'Success';
        }
        else if ($registrationStatus == 1)
        {
            return 'UsernameTaken';
        }
        else
        {
            return 'Error';
        }
    }

    public function handleWriteError(AgaviRequestDataHolder $rd)
    {
        error_log("Handling write errors for register");
        // Check for validation errors and add them to an array
        $report = $this->getContainer()->getValidationManager()->getReport();
        $errors = array();
        if ($report->byArgument('username')->has())
        {
            error_log("report contains errors for username");
            $usernameErrors = array();
            foreach ($report->byArgument('username')->getErrors() as $error)
            {
                array_push($usernameErrors, $error->getMessage());
            }
            $errors['usernameErrors'] = $usernameErrors;
        }
        if ($report->byArgument('password')->has())
        {
            error_log("report contains errors for password");
            $passwordErrors = array();
            foreach ($report->byArgument('password')->getErrors() as $error)
            {
                array_push($passwordErrors, $error->getMessage());
            }
            $errors['passwordErrors'] = $passwordErrors;
        }
        if ($report->byArgument('email')->has())
        {
            error_log("report contains errors for email");
            $emailErrors = array();
            foreach ($report->byArgument('email')->getErrors() as $error)
            {
                array_push($emailErrors, $error->getMessage());
            }
            $errors['emailErrors'] = $emailErrors;
        }
        if ($report->byArgument('firstName')->has())
        {
            error_log("report contains errors for first name");
            $firstNameErrors = array();
            foreach ($report->byArgument('firstName')->getErrors() as $error)
            {
                array_push($firstNameErrors, $error->getMessage());
            }
            $errors['firstNameErrors'] = $firstNameErrors;
        }
        if ($report->byArgument('lastName')->has())
        {
            error_log("report contains errors for last name");
            $lastNameErrors = array();
            foreach ($report->byArgument('lastName')->getErrors() as $error)
            {
                array_push($lastNameErrors, $error->getMessage());
            }
            $errors['lastNameErrors'] = $lastNameErrors;
        }
        $this->setAttribute('errors', $errors);
        return 'Invalid';
    }
}

?>
