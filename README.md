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

# Getting Friend Details
Send a HTTP GET request to teamkevin.me/Friends/Get
The username, password and friend's username should be passed in as HTTP variables with the respective names "username", "password" and "friendUsername"
Make sure to set the user agent to "ShoppingWithFriendsApp"
The possible responses are as follows:
```
<response>
    <satus>success</satus>
    <friend>
        <friendUsername>testUserB</friendUsername>
        <friendFirstName>Test</friendFirstName>
        <friendLastName>UserB</friendLastName>
        <friendEmail>testUserB@site.com</friendEmail>
        <friendRating>4.25</friendRating>
        <friendReportCount>4</friendReportCount>
    </friend>
</response>
```
This indicates a successful friend details response
The data contained in the friend tags will of course be the actual data for the friend given

```
<response>
    <status>invalidUser</status>
    <message>The given user does not exist.</message>
</response>
```
This indicates that the user is not actually a valid user
Receiving this message means that somehow the user authenticated, but was later not recognized as a database error
This most likely means that a backend error occurred
The text given in 'message' should be displayed in the app for the user to see

```
<response>
    <status>invalidFriend</status>
    <message>The given friend user does not exist.</message>
</response>
```
This indicates that the friend specified does not exist as a valid user in the database
The text given in 'message' should be displayed in the app for the user to see

```
<response>
    <status>invalidFriendship</status>
    <message>You are not friends with the specified user.</message>
</response>
```
This indicates that the two users are not actually friends, so you should not be able to see their data
The text given in 'message' should be displayed in the app for the user to see

```
<response>
    <status>notAuthorized</status>
    <message>You are not authorized to perform this action. Please check your username and password, then try again.</message>
</response>
```
This indicates that the user provided invalid login credentials
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

# Removing a Friend
Send a HTTP DELETE request to teamkevin.me/Friends/Remove
The username, password and friend's username should be passed in as HTTP variables with the respective names "username", "password" and "friendUsername"
Make sure to set the user agent to "ShoppingWithFriendsApp"
The possible responses are as follows:
```
<response>
    <satus>success</satus>
</response>
```
This indicates successful removal of the friend

```
<response>
    <status>invalidUser</status>
    <message>The given user does not exist.</message>
</response>
```
This indicates that the user is not actually a valid user
Receiving this message means that somehow the user authenticated, but was later not recognized as a database error
This most likely means that a backend error occurred
The text given in 'message' should be displayed in the app for the user to see

```
<response>
    <status>invalidFriend</status>
    <message>The given friend user does not exist.</message>
</response>
```
This indicates that the friend specified does not exist as a valid user in the database
The text given in 'message' should be displayed in the app for the user to see

```
<response>
    <status>removalError</status>
    <message>A database error occurred and the friendship could not be deleted. Please try again later.</message>
</response>
```
This indicates that some sort of database error occurred and the friendship could not be properly deleted
This really shouldn't happen and most likely indicates a probel with Propel ORM or the MySQL database
The text given in 'message' should be displayed in the app for the user to see

```
<response>
    <status>invalidFriendship</status>
    <message>You are not friends with the specified user.</message>
</response>
```
This indicates that the two users are not actually friends, so you should not be able to see their data
The text given in 'message' should be displayed in the app for the user to see

```
<response>
    <status>notAuthorized</status>
    <message>You are not authorized to perform this action. Please check your username and password, then try again.</message>
</response>
```
This indicates that the user provided invalid login credentials
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
