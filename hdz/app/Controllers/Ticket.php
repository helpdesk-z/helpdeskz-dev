<?php
/**
 * @package EvolutionScript
 * @author: EvolutionScript S.A.C.
 * @Copyright (c) 2010 - 2020, EvolutionScript.com
 * @link http://www.evolutionscript.com
 */

namespace App\Controllers;


use App\Libraries\reCAPTCHA;
use App\Libraries\Tickets;
use Config\Services;

class Ticket extends BaseController
{

    public function selectDepartment()
    {

        if($this->request->getPost('do') == 'submit'){
            $departments = Services::departments();
            $validation = Services::validation();
            $validation->setRule('department','department','required|is_natural_no_zero|is_not_unique[departments.id]');
            if($validation->withRequest($this->request)->run() == false){
                $error_msg = lang('Client.error.selectValidDepartment');
            }elseif(!$department = $departments->getByID($this->request->getPost('department'))){
                $error_msg = lang('Client.error.selectValidDepartment');
            }else{
                return redirect()->route('submit_ticket_department', [$department->id, url_title($department->name)]);
            }
        }
        return view('client/ticket_departments',[
            'error_msg' => isset($error_msg) ? $error_msg : null,
        ]);
    }
    public function create($department_id)
    {
        $departments = Services::departments();
        if(!$department = $departments->getByID($department_id)){
            return redirect()->route('submit_ticket');
        }

        $tickets = new Tickets();
        $validation = Services::validation();
        $reCAPTCHA = new reCAPTCHA();
        if($this->request->getPost('do') == 'submit'){
            $attachments = Services::attachments();
            if(!$this->client->isOnline()){
                $validation->setRule('fullname','fullname','required',[
                    'required' => lang('Client.error.enterFullName')
                ]);
                $validation->setRule('email','email','required|valid_email',[
                    'required' => lang('Client.error.enterValidEmail'),
                    'valid_email' => lang('Client.error.enterValidEmail')
                ]);
            }
            $validation->setRule('subject','subject', 'required',[
                'required' => lang('Client.error.enterSubject')
            ]);
            $validation->setRule('message','message', 'required',[
                'required' => lang('Client.error.enterYourMessage')
            ]);

            if($this->settings->config('ticket_attachment')){
                $max_size = $this->settings->config('ticket_file_size')*1024;
                $allowed_extensions = unserialize($this->settings->config('ticket_file_type'));
                $allowed_extensions = implode(',', $allowed_extensions);
                $validation->setRule('attachment', 'attachment', 'ext_in[attachment,'.$allowed_extensions.']|max_size[attachment,'.$max_size.']',[
                    'ext_in' => lang('Client.error.fileNotAllowed'),
                    'max_size' => lang_replace('Client.error.fileBig', ['%size%' => number_to_size($max_size*1024, 2)])
                ]);
            }

            $customFieldList = array();
            if($customFields = $tickets->customFieldsFromDepartment($department->id)){
                foreach ($customFields as $customField){
                    $value = '';
                    if(in_array($customField->type, ['text','textarea','password','email','date'])){
                        $value = $this->request->getPost('custom')[$customField->id];
                    }elseif(in_array($customField->type, ['radio','select'])){
                        $options = explode("\n", $customField->value);
                        $value = $options[$this->request->getPost('custom')[$customField->id]];
                    }elseif ($customField->type == 'checkbox'){
                        $options = explode("\n", $customField->value);
                        $checkbox_list = array();
                        if(is_array($this->request->getPost('custom')[$customField->id])){
                            foreach ($this->request->getPost('custom')[$customField->id] as $k){
                                $checkbox_list[] = $options[$k];
                            }
                            $value = implode(', ',$checkbox_list);
                        }
                    }
                    $customFieldList[] = [
                        'title' => $customField->title,
                        'value' => $value
                    ];
                    if($customField->required == '1'){
                        $validation->setRule('custom.'.$customField->id, $customField->title, 'required');
                    }
                }
            }

            if(!$reCAPTCHA->validate()){
                $error_msg = lang('Client.error.invalidCaptcha');
            }elseif($validation->withRequest($this->request)->run() == false){
                $error_msg = $validation->listErrors();
            }else{
                if($this->settings->config('ticket_attachment')){
                    if($uploaded_files = $attachments->ticketUpload()){
                        $files = $uploaded_files;
                    }
                }
                if($this->client->isOnline()){
                    $client_id = $this->client->getData('id');
                }else{
                    $client_id = $this->client->getClientID($this->request->getPost('fullname'), $this->request->getPost('email'));
                }

                $ticket_id = $tickets->createTicket($client_id, $this->request->getPost('subject'), $department->id);
                //Custom field
                $tickets->updateTicket([
                    'custom_vars' => serialize($customFieldList)
                ], $ticket_id);
                //Message
                $message_id = $tickets->addMessage($ticket_id, nl2br(esc($this->request->getPost('message'))), 0);

                //File
                if(isset($files)){
                    $attachments->addTicketFiles($ticket_id, $message_id, $files);
                }


                $ticket = $tickets->getTicket(['id' => $ticket_id]);
                $tickets->newTicketNotification($ticket);
                $tickets->staffNotification($ticket);
                $ticket_preview = sha1($ticket->id);
                $this->session->set('ticket_preview', $ticket_preview);
                return redirect()->route('ticket_preview', [$ticket->id, $ticket_preview]);
            }
        }

        return view('client/ticket_form',[
            'error_msg' => isset($error_msg) ? $error_msg : null,
            'department' => $department,
            'validation' => $validation,
            'captcha' => $reCAPTCHA->display(),
            'customFields' => $tickets->customFieldsFromDepartment($department->id)
        ]);
    }

    public function confirmedTicket($ticket_id, $preview_code)
    {
        if(!$this->session->has('ticket_preview')){
            return redirect()->route('submit_ticket');
        }

        if($this->session->get('ticket_preview') != $preview_code || sha1($ticket_id) != $preview_code){
            return redirect()->route('submit_ticket');
        }

        $tickets = new Tickets();
        if(!$ticket = $tickets->getTicket(['id'=>$ticket_id])){
            return redirect()->route('submit_ticket');
        }

        return view('client/ticket_confirmation',[
            'ticket' => $ticket
        ]);
    }

    public function clientTickets()
    {
        $tickets = new Tickets();
        $pagination = $tickets->clientTickets($this->client->getData('id'));
        return view('client/tickets',[
            'result_data' => $pagination['result'],
            'pager' => $pagination['pager'],
            'error_msg' => isset($error_msg) ? $error_msg : null
        ]);
    }

    public function clientShow($ticket_id, $page=1)
    {
        $tickets = new Tickets();
        $attachments = Services::attachments();
        if(!$info = $tickets->getTicket(['id' => $ticket_id,'user_id' => $this->client->getData('id')])){
            $this->session->setFlashdata('error_msg', lang('Client.viewTickets.notFound'));
            return redirect()->route('view_tickets');
        }
        if($this->request->getGet('download')){
            if(!$file = $attachments->getRow(['id' => $this->request->getGet('download'),'ticket_id' => $info->id])){
                return view('client/error',[
                    'title' => lang('Client.error.fileNotFound'),
                    'body' => lang('Client.error.fileNotFoundMsg'),
                    'footer' => ''
                ]);
            }
            return $attachments->download($file);
        }

        if($this->request->getPost('do') == 'reply')
        {
            $validation = Services::validation();
            $validation->setRule('message','message','required',[
                'required' => lang('Client.error.enterYourMessage')
            ]);

            if($this->settings->config('ticket_attachment')){
                $max_size = $this->settings->config('ticket_file_size')*1024;
                $allowed_extensions = unserialize($this->settings->config('ticket_file_type'));
                $allowed_extensions = implode(',', $allowed_extensions);
                $validation->setRule('attachment', 'attachment', 'ext_in[attachment,'.$allowed_extensions.']|max_size[attachment,'.$max_size.']',[
                    'ext_in' => lang('Client.error.fileNotAllowed'),
                    'max_size' => lang_replace('Client.error.fileBig', ['%size%' => number_to_size($max_size*1024, 2)])
                ]);
            }
            if($validation->withRequest($this->request)->run() == false) {
                $error_msg = $validation->listErrors();
            }else{
                if($this->settings->config('ticket_attachment')){
                    if($uploaded_files = $attachments->ticketUpload()){
                        $files = $uploaded_files;
                    }
                }
                //Message
                $message_id = $tickets->addMessage($info->id, nl2br(esc($this->request->getPost('message'))));
                $tickets->updateTicketReply($info->id, $info->status);
                //File
                if(isset($files)){
                    $attachments->addTicketFiles($info->id, $message_id, $files);
                }

                $tickets->staffNotification($info);
                $this->session->setFlashdata('form_success',lang('Client.viewTickets.replySent'));
                return redirect()->to(current_url());
            }
        }

        $data = $tickets->getMessages($info->id);

        return view('client/ticket_view', [
            'ticket' => $info,
            'result_data' => $data['result'],
            'pager' => $data['pager'],
            'ticket_status' => lang('Client.form.'.$tickets->statusName($info->status)),
            'error_msg' => isset($error_msg) ? $error_msg : null,
        ]);
    }


}