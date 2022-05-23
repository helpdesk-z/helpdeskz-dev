Ticket Messages
===============

Add a new message
-----------------

.. http:post:: /api/messages/create

    :query numeric ticket_id: Ticket ID
    :query string replier: [staff,user] The person who is replying this ticket
    :query numeric staff_id: Staff ID, required only if replier is `staff´
    :query string message: Message content
    :query file attachment[]: (optional) Attachment file
    :query boolean close: 1=Close ticket after reply, this is only valid if replier is `staff´

    **Example request**:

    .. content-tabs::

        .. tab-container:: tab1
            :title: cURL

            .. sourcecode:: bash

                curl \
                -X POST \
                -H 'Token: <token>' https://demo.helpdeskz.com/api/messages/create/ \
                -F 'ticket_id="1"' \
                -F 'replier="staff"' \
                -F 'staff_id="1"' \
                -F 'message="Answering a ticket."' \
                -F 'attachment[]=@"/home/andres/Images/homebg.jpg"' \
                -F 'close="0"'

        .. tab-container:: tab2
            :title: PHP

            .. sourcecode:: php

                <?php
                $curl = curl_init();
                curl_setopt_array($curl, array(
                  CURLOPT_URL => 'ttps://demo.helpdeskz.com/api/messages/create/',
                  CURLOPT_RETURNTRANSFER => true,
                  CURLOPT_CUSTOMREQUEST => 'POST',
                  CURLOPT_POSTFIELDS => array('ticket_id' => '1','replier' => 'staff','staff_id' => '1','message' => 'Answering a ticket','attachment[]'=> new CURLFILE('/home/andres/Images/homebg.jpg'),'close' => '0'),
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
            "message": "Message was created and added to ticket."
        }

Retrieve messages from a ticket
-------------------------------

.. http:get:: /api/messages/show/<ticket_id>/

    **Example request**:

    .. content-tabs::

        .. tab-container:: tab1
            :title: cURL

            .. sourcecode:: bash

                curl \
                -H 'Token: <token>' https://demo.helpdeskz.com/api/messages/show/1

        .. tab-container:: tab2
            :title: PHP

            .. sourcecode:: php

                <?php
                $curl = curl_init();
                curl_setopt_array($curl, array(
                  CURLOPT_URL => 'https://demo.helpdeskz.com/api/messages/show/1',
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
            "pages": 1,
            "total_replies": 3,
            "messages": [
                {
                    "id": "3",
                    "date": "1611619936",
                    "customer": "1",
                    "staff_id": "0",
                    "message": "This is user answer"
                },
                {
                    "id": "2",
                    "date": "1611619889",
                    "customer": "0",
                    "staff_id": "1",
                    "message": "Answering a ticket"
                },
                {
                    "id": "1",
                    "date": "1611619574",
                    "customer": "1",
                    "staff_id": "0",
                    "message": "This is a test message."
                }
            ]
        }