Departments
===========

Create a new department
-----------------------

.. http:post:: /api/departments/create

    :query string name: Department name
    :query boolean private: 0=public department, 1=private department

    **Example request**:

    .. content-tabs::

        .. tab-container:: tab1
            :title: cURL

            .. sourcecode:: bash

                curl \
                -X POST \
                -H 'Token: <token>' https://demo.helpdeskz.com/api/departments/create/ \
                -F 'name="Bug report"' \
                -F 'private="0"'

        .. tab-container:: tab2
            :title: PHP

            .. sourcecode:: php

                <?php
                $curl = curl_init();
                curl_setopt_array($curl, array(
                  CURLOPT_URL => 'https://demo.helpdeskz.com/api/departments/create/',
                  CURLOPT_RETURNTRANSFER => true,
                  CURLOPT_CUSTOMREQUEST => 'POST',
                  CURLOPT_POSTFIELDS => array('name' => 'Bug report','private' => '0'),
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
            "department_id": 4,
            "message": "Department was created."
        }

Retrieve a list of all departments
-----------------------------------

.. http:get:: /api/departments/

    **Example request**:

    .. content-tabs::

        .. tab-container:: tab1
            :title: cURL

            .. sourcecode:: bash

                curl \
                -H 'Token: <token>' https://demo.helpdeskz.com/api/departments

        .. tab-container:: tab2
            :title: PHP

            .. sourcecode:: php

                <?php
                $curl = curl_init();
                curl_setopt_array($curl, array(
                  CURLOPT_URL => 'https://demo.helpdeskz.com/api/departments',
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
            "departments": [
                {
                    "id": "1",
                    "name": "General",
                    "private": "0"
                },
                {
                    "id": "2",
                    "name": "Advertising",
                    "private": "0"
                },
                {
                    "id": "3",
                    "name": "Sales",
                    "private": "0"
                },
                {
                    "id": "4",
                    "name": "Bug report",
                    "private": "0"
                }
            ]
        }

Retrieve details of department by ID
------------------------------------

.. http:get:: /api/departments/show/<user_id>

    **Example request**:

    .. content-tabs::

        .. tab-container:: tab1
            :title: cURL

            .. sourcecode:: bash

                curl \
                -H 'Token: <token>' https://demo.helpdeskz.com/api/departments/show/4

        .. tab-container:: tab2
            :title: PHP

            .. sourcecode:: php

                <?php
                $curl = curl_init();
                curl_setopt_array($curl, array(
                  CURLOPT_URL => 'https://demo.helpdeskz.com/api/departments/show/4',
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
            "department": {
                "id": "4",
                "name": "Bug report",
                "private": "0"
            }
        }

Update department
------------------

.. http:post:: /api/departments/update/<department_id>

    :query string name: New department name
    :query boolean private: 0=public department, 1=private department

    **Example request**:

    .. content-tabs::

        .. tab-container:: tab1
            :title: cURL

            .. sourcecode:: bash

                curl \
                -X POST \
                -H 'Token: <token>' https://demo.helpdeskz.com/api/departments/update/4 \
                -F 'name="Issues report"'
                -F 'private="0"'

        .. tab-container:: tab2
            :title: PHP

            .. sourcecode:: php

                <?php
                $curl = curl_init();
                curl_setopt_array($curl, array(
                  CURLOPT_URL => 'https://demo.helpdeskz.com/api/departments/update/1',
                  CURLOPT_RETURNTRANSFER => true,
                  CURLOPT_CUSTOMREQUEST => 'POST',
                  CURLOPT_POSTFIELDS => array('name' => 'Issues report', 'private' => '0'),
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
            "message": "Department was updated."
        }

Delete department
------------------

.. http:post:: /api/departments/delete/<department_id>

    **Example request**:

    .. content-tabs::

        .. tab-container:: tab1
            :title: cURL

            .. sourcecode:: bash

                curl \
                -X POST \
                -H 'Token: <token>' https://demo.helpdeskz.com/api/departments/delete/4

        .. tab-container:: tab2
            :title: PHP

            .. sourcecode:: php

                <?php
                $curl = curl_init();
                curl_setopt_array($curl, array(
                  CURLOPT_URL => 'https://demo.helpdeskz.com/api/departments/delete/4',
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
            "message": "Department and its tickets were removed."
        }


    .. note::

       With this action, all tickets from this department will be removed.