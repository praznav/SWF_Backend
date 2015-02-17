# CS2340-Backend
Repository for the Agavi backend for Shopping With Friends

# Registering a User
Send a HTTP POST request to teamkevin.me/Users/Register
The username, password, email, first name and last name should be passed in as HTTP variables with the respective names "username", "password", "email", "firstName", and "lastName"
Make sure to set the user agent to "ShoppingWithFriendsApp"
The possible responses are as follows:
```
<response>
  <status>success</status>
</response>
```
This indicates that the user was successfully registered in the system with no errors

```
<response>
    <status>taken</status>
    <message>The username you requested is currently taken. Please try a different one.</message>
</response>
```
This indicates that the username the user requested is already registered in the system

The following shows errors none of the parameters are set
```
<response>
    <status>invalid</status>
    <usernameErrors>
        <error>You must enter a username.</error>
    </usernameErrors>
    <passwordErrors>
        <error>You must enter a password.</error>
    </passwordErrors>
    <emailErrors>
        <error>You must enter an email.</error>
    </emailErrors>
    <firstNameErrors>
        <error>You must enter a first name.</error>
    </firstNameErrors>
    <lastNameErrors>
        <error>You must enter a last name.</error>
    </lastNameErrors>
</response>
```
If the invalid status is received, you should check for each type of error given, and display the text given in that error types tag

All of these responses should also come with HTTP response code 200

In the event of a server error, the response code will be 500 and the following response will be given:
```
<response>
  <status>error</status>
  <message>A server error occurred. Please try again later.</message>
</response>
```
The text given in 'message' should be displayed in the app for the user to see


# Logging In
Send a HTTP GET request to teamkevin.me/Users/Login
The username and password should be passed in as HTTP variables with the respective names "username" and "password"
Make sure to set the user agent to "ShoppingWithFriendsApp"
The possible responses are as follows:
```
<response>
  <status>success</status>
</response>
```
This indicates a correct login

```
<response>
  <status>incorrect</status>
  <message>The given username or password is invalid.</message>
</response>
```
This indicates that the entered username or password is invalid
The text given in 'message' should be displayed in the app for the user to see

```
<response>
  <status>locked</status>
  <message>The account you attempted to log in to is currently locked. Please contact an administrator in order to unlock it.</message>
</response>
```
The account is locked and needs to be unlocked by an admin
The text given in 'message' should be displayed in the app for the user to see

All of these responses should also come with HTTP response code 200

In the event of a server error, the response code will be 500 and the following response will be given:
```
<response>
  <status>error</status>
  <message>A server error occurred. Please try again later.</message>
</response>
```
The text given in 'message' should be displayed in the app for the user to see

In the event of a 500 without a response or another HTTP code, something has gone very wrong with the server and the user should be notified of it
