API activation
==============

- In Staff Panel, go to Setup -> API configuraiton.
- Create a new API.
- Select the permissions for your new API.
- Enter the IP that will connect with your API.
- After creation, the system will generate your API token.

Authentication and authorization
---------------------------------

All endpoints require authentication.

Token
~~~~~

The ``Authorization`` HTTP header can be specified with ``Token: <your-access-token>``.