Attachments
===========

Retrieve list of attachments
----------------------------

.. http:get:: /api/attachments/

    :query numeric ticket_id: (optional) Ticket ID
    :query numeric msg_id: (optional) Message ID

    **Example request**:

    .. content-tabs::

        .. tab-container:: tab1
            :title: cURL

            .. sourcecode:: bash

                curl \
                -H 'Token: <token>' https://demo.helpdeskz.com/api/attachments/

        .. tab-container:: tab2
            :title: PHP

            .. sourcecode:: php

                <?php
                $curl = curl_init();
                curl_setopt_array($curl, array(
                  CURLOPT_URL => 'https://demo.helpdeskz.com/api/attachments/',
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
            "files": [
                {
                    "id": "2",
                    "name": "homebg.jpg",
                    "filetype": "image/jpeg",
                    "msg_id": "2",
                    "size": "116748"
                },
                {
                    "id": "3",
                    "name": "homebg.jpg",
                    "filetype": "image/jpeg",
                    "msg_id": "3",
                    "size": "116748"
                }
            ]
        }

Retrieve file content from an attachment
----------------------------------------

.. http:get:: /api/attachments/show/<attachment_id>

    **Example request**:

    .. content-tabs::

        .. tab-container:: tab1
            :title: cURL

            .. sourcecode:: bash

                curl \
                -H 'Token: <token>' https://demo.helpdeskz.com/api/attachments/show/1

        .. tab-container:: tab2
            :title: PHP

            .. sourcecode:: php

                <?php
                $curl = curl_init();
                curl_setopt_array($curl, array(
                  CURLOPT_URL => 'https://demo.helpdeskz.com/api/attachments/show/1',
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
            "name": "hdz.png",
            "filetype": "image/png",
            "size": "13497",
            "content": "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"
        }

Delete attachment
-----------------

.. http:post:: /api/attachments/delete/<attachment_id>

    **Example request**:

    .. content-tabs::

        .. tab-container:: tab1
            :title: cURL

            .. sourcecode:: bash

                curl \
                -X POST \
                -H 'Token: <token>' https://demo.helpdeskz.com/api/attachments/delete/1

        .. tab-container:: tab2
            :title: PHP

            .. sourcecode:: php

                <?php
                $curl = curl_init();
                curl_setopt_array($curl, array(
                  CURLOPT_URL => 'https://demo.helpdeskz.com/api/attachments/delete/1',
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
            "message": "File was removed from servers."
        }