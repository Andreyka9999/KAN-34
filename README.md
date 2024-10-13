## About Project

API for user registration and authentication using Laravel Sanctum. It allows users to register, log in and log out using token-based authentication.


## Functionality

1. User registration (POST/api/register)
A new user can register in the system by providing their name, email and password.

- Data validation (name, email and password are mandatory, password confirmation).
- If the data is valid, a new entry in the user table is created and a token is returned for further requests.
- The access token is returned in the response to be used for authentication of further requests.

2. User authentication (POST/api/login)
Allows a registered user to log in using email and password.

- Data validation: verification of email and password.
- Checking the existence of the user and the correctness of the password.
- If the data is correct, a new token for the user is created and returned.

3. Logout (POST/api/logout)
Exit from system and delete curently users tokens.

- Deletes all active user tokens, making them invalid.
- Requires the user to be authenticated with Sanctum.

4. Token-based authentication using Laravel Sanctum:
- Upon successful registration or login, the user receives a personalized access token.
- This token is used for authentication in future requests that require access to secure routes.
- Tokens are automatically deleted when the user logs out of the system.