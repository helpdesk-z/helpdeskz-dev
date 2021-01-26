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

.. http:post:: /api/users/create

    Create a new user

    **Example request**:

    .. content-tabs::

        .. tab-container:: tab1
            :title: cURL

            .. sourcecode:: bash

                curl \
                -X POST \
                -H 'Token: <token>' https://demo.helpdeskz.com/api/users/ \
                -F 'fullname="John Doe"' \
                -F 'email="john.doe@demo.com"' \
                -F 'notify="0"'

        .. tab-container:: tab2
            :title: PHP

            .. sourcecode:: php

                <?php
                $curl = curl_init();
                curl_setopt_array($curl, array(
                  CURLOPT_URL => 'http://helpdeskz.web/api/users/create',
                  CURLOPT_RETURNTRANSFER => true,
                  CURLOPT_CUSTOMREQUEST => 'POST',
                  CURLOPT_POSTFIELDS => array('fullname' => 'John Doe','email' => 'john.doe@demo.com','notify' => '1'),
                  CURLOPT_HTTPHEADER => array(
                    'Token: QYXTgwMGO7WRKU1f2BDpbAFrdmyjz4eJkZ8CoVls3IahqLS5tn6PciNExHvu'
                  ),
                ));
                $response = curl_exec($curl);
                curl_close($curl);

    **Example response**:

    .. sourcecode:: json

        {
            "success": 1,
            "user_id": 1,
            "message": "User account was created."
        }


.. http:get:: /api/users/

    Retrieve a list of all users.

    **Example request**:

    .. content-tabs::

        .. tab-container:: tab1
            :title: cURL

            .. sourcecode:: bash

                curl \
                -H 'Token: <token>' https://demo.helpdeskz.com/api/users

        .. tab-container:: tab2
            :title: PHP

            .. sourcecode:: php

                <?php
                $curl = curl_init();
                curl_setopt_array($curl, array(
                  CURLOPT_URL => 'https://demo.helpdeskz.com/api/users',
                  CURLOPT_RETURNTRANSFER => true,
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