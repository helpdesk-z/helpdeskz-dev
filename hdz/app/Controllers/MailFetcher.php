<?php
/**
 * @package EvolutionScript
 * @author: EvolutionScript S.A.C.
 * @Copyright (c) 2010 - 2020, EvolutionScript.com
 * @link http://www.evolutionscript.com
 */

namespace App\Controllers;


use App\Libraries\Attachments;
use App\Libraries\Emails;
use App\Libraries\Tickets;
use CodeIgniter\Files\File;
use PhpImap\Mailbox;

class MailFetcher extends BaseController
{
    public function fetch()
    {
        $config = \HTMLPurifier_Config::createDefault();
        $html_purifier = new \HTMLPurifier($config);
        $attach_dir = WRITEPATH.'attachments';
        $emails = new Emails();
        $tickets = new Tickets();
        if($list = $emails->getFetcher()){
            foreach ($list as $email)
            {
                $mailbox = new Mailbox(
                    '{'.$email->imap_host.':'.$email->imap_port.'/'.$email->incoming_type.'/ssl/novalidate-cert}INBOX', // IMAP server and mailbox folder
                    $email->imap_username, // Username for the before configured mailbox
                    $email->imap_password // Password for the before configured username
                );
                try{
                    $mailsIds = $mailbox->searchMailbox('ALL');
                }catch (\PhpImap\Exceptions\ConnectionException $ex){
                    log_message('error','IMAP connection failed: '.$ex);
                    return;
                }
                if(!$mailsIds){
                    return;
                }
                $mailbox->setAttachmentsDir($attach_dir);
                foreach ($mailsIds as $k => $v){
                    $mail = $mailbox->getMail($mailsIds[$k]);
                    $message = ($mail->textHtml) ? $html_purifier->purify($mail->textHtml) : $mail->textPlain;
                    $client_id = $this->client->getClientID($mail->fromName, $mail->fromAddress);
                    if(!$ticket = $tickets->getTicketFromEmail($client_id, $mail->subject)){
                        $ticket_id = $tickets->createTicket(
                            $client_id,
                            $mail->subject,
                            $email->department_id
                        );
                        $message_id = $tickets->addMessage($ticket_id, $message);
                        $ticket = $tickets->getTicket(['id' => $ticket_id]);
                    }else{
                        $ticket_id = $ticket->id;
                        $message_id = $tickets->addMessage($ticket_id, $message);
                        $tickets->updateTicketReply($ticket_id, $ticket->status);
                    }
                    $tickets->staffNotification($ticket);
                    //Attachments
                    $attachments = new Attachments();
                    if(!empty($mail->getAttachments())){
                        foreach ($mail->getAttachments() as $file){
                            if(file_exists($file->filePath)){
                                $file = new File($file->filePath);
                                $size = $file->getSize();
                                $file_type = $file->getMimeType();
                                $filename = $file->getRandomName();
                                $file->move($attach_dir, $filename);
                                #$real_path = $file->filePath;
                                #$ext = pathinfo($real_path, PATHINFO_EXTENSION);
                                #$filename = time().'_'.random_string('md5').'.'.$ext;
                                #rename($real_path, $attach_dir.'/'.$filename);
                                //$file_info = get_file_info($attach_dir.'/'.$filename);
                                //$size = $file_info['size'];
                                //$file_type = get_mime_by_extension($attach_dir.'/'.$filename);
                                $attachments->addFromTicket(
                                    $ticket_id,
                                    $message_id,
                                    $file->getFilename(),
                                    $filename,
                                    $size,
                                    $file_type
                                );
                            }
                        }
                    }
                    $mailbox->deleteMail($mail->id);
                }
                $mailbox->disconnect();
            }
        }
    }
}