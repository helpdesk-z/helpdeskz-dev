Users
=====

Create a new user
-----------------

.. http:post:: /api/users/create

    :query string fullname: Client's Full Name
    :query string email: Client's Email address
    :query boolean notify: 1 = Client will receive an email with login information

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
                    'Token: <token>'
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

Retrieve a list of all users
----------------------------

.. http:get:: /api/users/

    :query string email: Find a client by email
    :query numeric page: Page query is used to view next page

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

Retrieve details of user by ID
-------------------------------

.. http:get:: /api/users/show/<user_id>

    **Example request**:

    .. content-tabs::

        .. tab-container:: tab1
            :title: cURL

            .. sourcecode:: bash

                curl \
                -H 'Token: <token>' https://demo.helpdeskz.com/api/users/show/1

        .. tab-container:: tab2
            :title: PHP

            .. sourcecode:: php

                <?php
                $curl = curl_init();
                curl_setopt_array($curl, array(
                  CURLOPT_URL => 'https://demo.helpdeskz.com/api/users/show/1',
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
            "user_data": {
                "id": "1",
                "fullname": "John Doe",
                "email": "john.doe@demo.com"
            }
        }

Update user account
--------------------

.. http:post:: /api/users/update/<user_id>

    :query string new_email: New client's email address

    **Example request**:

    .. content-tabs::

        .. tab-container:: tab1
            :title: cURL

            .. sourcecode:: bash

                curl \
                -X POST \
                -H 'Token: <token>' https://demo.helpdeskz.com/api/users/update/1 \
                -F 'new_email="john.doe123@demo.com"'

        .. tab-container:: tab2
            :title: PHP

            .. sourcecode:: php

                <?php
                $curl = curl_init();
                curl_setopt_array($curl, array(
                  CURLOPT_URL => 'https://demo.helpdeskz.com/api/users/update/1',
                  CURLOPT_RETURNTRANSFER => true,
                  CURLOPT_CUSTOMREQUEST => 'POST',
                  CURLOPT_POSTFIELDS => array('new_email' => 'john.doe123@demo.com'),
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
            "message": "Email was changed."
        }

Delete user account
--------------------

.. http:post:: /api/users/delete/<user_id>

    **Example request**:

    .. content-tabs::

        .. tab-container:: tab1
            :title: cURL

            .. sourcecode:: bash

                curl \
                -X POST \
                -H 'Token: <token>' https://demo.helpdeskz.com/api/users/delete/1

        .. tab-container:: tab2
            :title: PHP

            .. sourcecode:: php

                <?php
                $curl = curl_init();
                curl_setopt_array($curl, array(
                  CURLOPT_URL => 'https://demo.helpdeskz.com/api/users/delete/1',
                  CURLOPT_RETURNTRANSFER => true,
                  CURLOPT_CUSTOMREQUEST => 'POST',
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
            "message": "Account was removed."
        }