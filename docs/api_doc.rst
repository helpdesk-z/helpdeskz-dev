HelpDeskZ API
==============

API allows to connect your HelpDeskZ with third party sites or applications.

API activation
---------------

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

Users
------

Users list
+++++++++++

.. http:get:: /api/users/

    Retrieve a list of all users.

    **Example request**:

    .. content-tabs::

        .. tab-container:: tab1
            :title: CURL

            .. sourcecode:: bash

            curl --location --request GET 'http://helpdeskz.web/api/users' \
            --header 'Token: <token>'

        .. tab-container:: tab2
            :title: PHP

            .. sourcecode:: php

                <?php
                $curl = curl_init();
                curl_setopt_array($curl, array(
                  CURLOPT_URL => 'http://helpdeskz.web/api/users',
                  CURLOPT_RETURNTRANSFER => true,
                  CURLOPT_ENCODING => '',
                  CURLOPT_MAXREDIRS => 10,
                  CURLOPT_TIMEOUT => 0,
                  CURLOPT_FOLLOWLOCATION => true,
                  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                  CURLOPT_CUSTOMREQUEST => 'GET',
                  CURLOPT_HTTPHEADER => array(
                    'Token: <token>'
                  ),
                ));
                $response = curl_exec($curl);
                curl_close($curl);

    **Example response**:

    .. sourcecode:: json

        {
            "success": 1,
            "total_users": 2,
            "total_pages": 1,
            "users": [
                {
                    "id": "2",
                    "fullname": "John Doe",
                    "email": "john.doe@demo.com"
                },
                {
                    "id": "1",
                    "fullname": "John Doe",
                    "email": "john.doe123@demo.com"
                }
            ]
        }