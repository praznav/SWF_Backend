# CS2340-Backend
Repository for the Agavi backend for Shopping With Friends


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
