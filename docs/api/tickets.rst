Support Tickets
===============

Create a new ticket
--------------------

.. http:post:: /api/tickets/create

    :query string opener: [user,staff] The person who is opening this ticket
    :query numeric user_id: User ID
    :query numeric staff_id: Staff ID, it is required if opener is  `staff´
    :query numeric department_id: Department ID
    :query string subject: Ticket subject
    :query string body: Ticket message
    :query file attachment[]: (optional) Attachment file
    :query boolean notify: 1=User will receive an email with ticket information

    **Example request**:

    .. content-tabs::

        .. tab-container:: tab1
            :title: cURL

            .. sourcecode:: bash

                curl \
                -X POST \
                -H 'Token: <token>' https://demo.helpdeskz.com/api/tickets/create/ \
                -F 'opener="staff"' \
                -F 'user_id="1"' \
                -F 'staff_id="1"' \
                -F 'department_id="4"' \
                -F 'subject="Hello world"' \
                -F 'body="This is a test message."' \
                -F 'attachment[]=@"/home/andres/Images/hdz.png"' \
                -F 'notify="0"'

        .. tab-container:: tab2
            :title: PHP

            .. sourcecode:: php

                <?php
                $curl = curl_init();
                curl_setopt_array($curl, array(
                  CURLOPT_URL => 'ttps://demo.helpdeskz.com/api/tickets/create/',
                  CURLOPT_RETURNTRANSFER => true,
                  CURLOPT_CUSTOMREQUEST => 'POST',
                  CURLOPT_POSTFIELDS => array('opener' => 'user','user_id' => '1','staff_id' => '1','department_id' => '4','subject' => 'Hello world','body' => 'This is a test message.','attachment[]'=> new CURLFILE('/home/andres/Images/hdz.png'),'notify' => '0'),
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
            "ticket_id": 1,
            "message": "Ticket was created."
        }

Retrieve a list of all tickets
------------------------------

.. http:get:: /api/tickets/

    :query numeric department_id: (optional) Department ID
    :query numeric user_id: (optional) User ID
    :query numeric status_id: (optional) 1: Open, 2: Answered, 3: Awaiting reply, 4: In progress, 5: Closed

    **Example request**:

    .. content-tabs::

        .. tab-container:: tab1
            :title: cURL

            .. sourcecode:: bash

                curl \
                -H 'Token: <token>' https://demo.helpdeskz.com/api/tickets

        .. tab-container:: tab2
            :title: PHP

            .. sourcecode:: php

                <?php
                $curl = curl_init();
                curl_setopt_array($curl, array(
                  CURLOPT_URL => 'https://demo.helpdeskz.com/api/tickets',
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
            "total_tickets": 1,
            "pages": 1,
            "tickets": [
                {
                    "id": "1",
                    "user_id": "1",
                    "department_id": "4",
                    "subject": "Hello world",
                    "date": "1611619574",
                    "last_update": "1611619936",
                    "status": "4",
                    "replies": "0",
                    "user_fullname": "John Doe",
                    "department_name": "Issues report"
                }
            ]
        }

Retrieve details of ticket by ID
--------------------------------

.. http:get:: /api/tickets/show/<ticket_id>

    **Example request**:

    .. content-tabs::

        .. tab-container:: tab1
            :title: cURL

            .. sourcecode:: bash

                curl \
                -H 'Token: <token>' https://demo.helpdeskz.com/api/tickets/show/1

        .. tab-container:: tab2
            :title: PHP

            .. sourcecode:: php

                <?php
                $curl = curl_init();
                curl_setopt_array($curl, array(
                  CURLOPT_URL => 'https://demo.helpdeskz.com/api/tickets/show/1',
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
            "ticket": {
                "id": "1",
                "user_id": "1",
                "department_id": "4",
                "subject": "Hello world",
                "date": "1611619574",
                "last_update": "1611619574",
                "status": "1",
                "replies": "0",
                "user_fullname": "John Doe",
                "department_name": "Issues report"
            }
        }

Update ticket
-------------

.. http:post:: /api/tickets/update/<ticket_id>

    :query numeric department_id: (optional) Department ID
    :query numeric status_id: (optional) 1: Open, 2: Answered, 3: Awaiting reply, 4: In progress, 5: Closed

    **Example request**:

    .. content-tabs::

        .. tab-container:: tab1
            :title: cURL

            .. sourcecode:: bash

                curl \
                -X POST \
                -H 'Token: <token>' https://demo.helpdeskz.com/api/tickets/update/1 \
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
            "message": "Ticket was updated."
        }

Delete department
------------------

.. http:post:: /api/tickets/delete/<ticket_id>

    **Example request**:

    .. content-tabs::

        .. tab-container:: tab1
            :title: cURL

            .. sourcecode:: bash

                curl \
                -X POST \
                -H 'Token: <token>' https://demo.helpdeskz.com/api/tickets/delete/1

        .. tab-container:: tab2
            :title: PHP

            .. sourcecode:: php

                <?php
                $curl = curl_init();
                curl_setopt_array($curl, array(
                  CURLOPT_URL => 'https://demo.helpdeskz.com/api/tickets/delete/1',
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
            "message": "Ticket was removed."
        }


    .. note::

       With this action, ticket and its messages and attachments will be be removed.