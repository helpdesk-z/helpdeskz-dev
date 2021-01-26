Staff
=====

Retrieve a list of all staff users
----------------------------------

.. http:get:: /api/staff/

    :query string username: Find a user by username

    **Example request**:

    .. content-tabs::

        .. tab-container:: tab1
            :title: cURL

            .. sourcecode:: bash

                curl \
                -H 'Token: <token>' https://demo.helpdeskz.com/api/staff

        .. tab-container:: tab2
            :title: PHP

            .. sourcecode:: php

                <?php
                $curl = curl_init();
                curl_setopt_array($curl, array(
                  CURLOPT_URL => 'https://demo.helpdeskz.com/api/staff',
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
            "users": [
                {
                    "id": "1",
                    "username": "admin",
                    "fullname": "Andres Mendoza",
                    "email": "andres@demo.com",
                    "registration": "1611613586",
                    "last_login": "1611618058"
                }
            ]
        }

Retrieve details of staff user by ID
------------------------------------

.. http:get:: /api/staff/show/<staff_id>

    **Example request**:

    .. content-tabs::

        .. tab-container:: tab1
            :title: cURL

            .. sourcecode:: bash

                curl \
                -H 'Token: <token>' https://demo.helpdeskz.com/api/staff/show/1

        .. tab-container:: tab2
            :title: PHP

            .. sourcecode:: php

                <?php
                $curl = curl_init();
                curl_setopt_array($curl, array(
                  CURLOPT_URL => 'https://demo.helpdeskz.com/api/staff/show/1',
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
            "staff_data": {
                "id": "1",
                "username": "admin",
                "fullname": "Andres Mendoza",
                "email": "andres@demo.com",
                "registration": "1611613586",
                "last_login": "1611618058"
            }
        }

Staff Authentication
--------------------

.. http:post:: /api/staff/auth

    :query string username: Staff username
    :query string password: Staff password
    :query numeric two_factor: Two-Factor Authentication code, this is required if two-factor authentication is active in account
    :query string ip_address: IP Address of client

    **Example request**:

    .. content-tabs::

        .. tab-container:: tab1
            :title: cURL

            .. sourcecode:: bash

                curl \
                -X POST \
                -H 'Token: <token>' https://demo.helpdeskz.com/api/staff/auth/ \
                -F 'username="admin"' \
                -F 'password="demo123"' \
                -F 'two_factor="815435"' \
                -F 'ip_address="127.0.0.1"'

        .. tab-container:: tab2
            :title: PHP

            .. sourcecode:: php

                <?php
                $curl = curl_init();
                curl_setopt_array($curl, array(
                  CURLOPT_URL => 'https://demo.helpdeskz.com/api/staff/auth/',
                  CURLOPT_RETURNTRANSFER => true,
                  CURLOPT_CUSTOMREQUEST => 'POST',
                  CURLOPT_POSTFIELDS => array('username' => 'admin','password' => 'demo123','two_factor' => '815435','ip_address' => '127.0.0.1'),
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
            "message": "You have been logged in."
        }