<?php
/**
 * @package EvolutionScript
 * @author: EvolutionScript S.A.C.
 * @Copyright (c) 2010 - 2020, EvolutionScript.com
 * @link http://www.evolutionscript.com
 */

namespace App\Controllers\Staff;


use App\Controllers\BaseController;
use App\Models\CannedModel;
use Config\Services;


class Tickets extends BaseController
{
    public function manage($page)
    {
        $tickets = new \App\Libraries\Tickets();

        if($this->request->getPost('action')){
            if(!is_array($this->request->getPost('ticket_id'))) {
                $error_msg = lang('Admin.error.noItemsSelected');
            }else{
                foreach ($this->request->getPost('ticket_id') as $ticket_id){
                    if(is_numeric($ticket_id)){
                        if($this->request->getPost('action') == 'remove'){
                            $tickets->deleteTicket($ticket_id);
                        }elseif($this->request->getPost('action') == 'update'){
                            if(is_numeric($this->request->getPost('department'))){
                                if(Services::departments()->isValid($this->request->getPost('department'))){
                                    $tickets->updateTicket([
                                        'department_id' => $this->request->getPost('department')
                                    ], $ticket_id);
                                }
                            }
                            if(is_numeric($this->request->getPost('status'))){
                                if(array_key_exists($this->request->getPost('status'), $tickets->statusList())){
                                    $tickets->updateTicket([
                                        'status' => $this->request->getPost('status')
                                    ], $ticket_id);
                                }
                            }
                            if(is_numeric($this->request->getPost('priority'))){
                                if($tickets->existPriority($this->request->getPost('priority'))){
                                    $tickets->updateTicket([
                                        'priority_id' => $this->request->getPost('priority')
                                    ], $ticket_id);
                                }
                            }
                        }
                    }
                }
                return redirect()->to(current_url(true));
            }
        }

        if($this->session->has('ticket_error')){
            $error_msg = $this->session->getFlashdata('ticket_error');
        }
        $result = $tickets->staffTickets($page);
        return view('staff/tickets',[
            'departments' => $this->staff->getDepartments(),
            'statuses' => $tickets->statusList(),
            'tickets_result' => $result['result'],
            'priorities' => $tickets->getPriorities(),
            'pager' => $result['pager'],
            'page_type' => $page,
            'error_msg' => isset($error_msg) ? $error_msg : null
        ]);
    }

    public function view($ticket_id)
    {
        $tickets = Services::tickets();
        if(!$ticket = $tickets->getTicket(['id' => $ticket_id])){
            $this->session->setFlashdata('ticket_error', lang('Admin.error.ticketNotFound'));
            return redirect()->route('staff_tickets');
        }
        $key = array_search($ticket->department_id, array_column($this->staff->getDepartments(),'id'));
        if(!is_numeric($key)){
            $this->session->setFlashdata('ticket_error', lang('Admin.error.ticketNotPermission'));
            return redirect()->route('staff_tickets');
        }
        $attachments = Services::attachments();
        #Download
        if($this->request->getGet('download')){
            if(!$file = $attachments->getRow(['id' => $this->request->getGet('download'),'ticket_id' => $ticket->id])){
                return view('client/error',[
                    'title' => lang('Client.error.fileNotFound'),
                    'body' => lang('Client.error.fileNotFoundMsg'),
                    'footer' => ''
                ]);
            }
            return $attachments->download($file);
        }
        elseif (is_numeric($this->request->getGet('delete_file'))){
            if(!$file = $attachments->getRow([
                'id' => $this->request->getGet('delete_file'),
                'ticket_id' => $ticket->id
            ])){
                return redirect()->to(current_url());
            }else{
                $attachments->deleteFile($file);
                $this->session->setFlashdata('ticket_update',lang('Admin.tickets.attachmentRemoved'));
                return redirect()->to(current_url());
            }
        }
        //Update Information
        if($this->request->getPost('do') == 'update_information') {
            $validation = Services::validation();
            $validation->setRules([
                'department' => 'required|is_natural_no_zero|is_not_unique[departments.id]',
                'status' => 'required|is_natural|in_list[' . implode(',', array_keys($tickets->statusList())) . ']',
                'priority' => 'required|is_natural_no_zero|is_not_unique[priority.id]'
            ], [
                'department' => [
                    'required' => lang('Admin.error.invalidDepartment'),
                    'is_natural_no_zero' => lang('Admin.error.invalidDepartment'),
                    'is_not_unique' => lang('Admin.error.invalidDepartment'),
                ],
                'status' => [
                    'required' => lang('Admin.error.invalidStatus'),
                    'is_natural' => lang('Admin.error.invalidStatus'),
                    'in_list' => lang('Admin.error.invalidStatus'),
                ],
                'priority' => [
                    'required' => lang('Admin.error.invalidPriority'),
                    'is_natural_no_zero' => lang('Admin.error.invalidPriority'),
                    'is_not_unique' => lang('Admin.error.invalidPriority')
                ]
            ]);
            if($validation->withRequest($this->request)->run() == false){
                $error_msg = $validation->listErrors();
            }else{
                $tickets->updateTicket([
                    'department_id' => $this->request->getPost('department'),
                    'status' => $this->request->getPost('status'),
                    'priority_id' => $this->request->getPost('priority'),
                ], $ticket->id);
                $this->session->setFlashdata('ticket_update', 'Ticket updated.');
                return redirect()->to(current_url());
            }
        }
        //Reply Ticket
        elseif ($this->request->getPost('do') == 'reply')
        {
            $validation = Services::validation();
            $validation->setRule('message','message','required',[
                'required' => lang('Admin.error.enterMessage')
            ]);

            if($this->settings->config('ticket_attachment')){
                $max_size = $this->settings->config('ticket_file_size')*1024;
                $allowed_extensions = unserialize($this->settings->config('ticket_file_type'));
                $allowed_extensions = implode(',', $allowed_extensions);
                $validation->setRule('attachment', 'attachment', 'ext_in[attachment,'.$allowed_extensions.']|max_size[attachment,'.$max_size.']',[
                    'ext_in' => lang('Admin.error.fileNotAllowed'),
                    'max_size' => lang_replace('Admin.error.fileBig', ['%size%' => number_to_size($max_size*1024, 2)])
                ]);
            }

            if($validation->withRequest($this->request)->run() == false){
                $error_msg = $validation->listErrors();
            }else{
                if ($this->settings->config('ticket_attachment')) {
                    if ($files_uploaded = $attachments->ticketUpload()) {
                        $files = $files_uploaded;
                    }
                }
                //Message
                $message = $this->request->getPost('message').$this->staff->getData('signature');
                $message_id = $tickets->addMessage($ticket->id, $message, $this->staff->getData('id'));

                //File
                if (isset($files)) {
                    $attachments->addTicketFiles($ticket->id, $message_id, $files);
                }
                $tickets->updateTicketReply($ticket->id, $ticket->status, true);
                if(!defined('HDZDEMO')){
                    $tickets->replyTicketNotification($ticket, $message, (isset($files) ? $files : null));
                }
                $this->session->setFlashdata('ticket_update', lang('Admin.tickets.messageSent'));
                return redirect()->to(current_url());
            }
        }
        elseif ($this->request->getPost('do') == 'delete_note'){
            $validation = Services::validation();
            $validation->setRule('note_id','note_id','required|is_natural_no_zero');
            if($validation->withRequest($this->request)->run() == false) {
                $error_msg = lang('Admin.tickets.invalidRequest');
            }elseif(!$note = $tickets->getNote($this->request->getPost('note_id'))) {
                $error_msg = lang('Admin.tickets.invalidRequest');
            }elseif ($this->staff->getData('admin') == 1 || $this->staff->getData('id') == $note->staff_id){
                $tickets->deleteNote($ticket->id, $this->request->getPost('note_id'));
                $this->session->setFlashdata('ticket_update', lang('Admin.tickets.noteRemoved'));
                return redirect()->to(current_url());
            }else{
                $error_msg = lang('Admin.tickets.invalidRequest');
            }
        }
        elseif ($this->request->getPost('do') == 'edit_note'){
            $validation = Services::validation();
            $validation->setRule('note_id','note_id','required|is_natural_no_zero');
            if($validation->withRequest($this->request)->run() == false) {
                $error_msg = lang('Admin.tickets.invalidRequest');
            }elseif ($this->request->getPost('new_note') == ''){
                $error_msg = lang('Admin.tickets.enterNote');
            }elseif(!$note = $tickets->getNote($this->request->getPost('note_id'))) {
                $error_msg = lang('Admin.tickets.invalidRequest');
            }elseif ($this->staff->getData('admin') == 1 || $this->staff->getData('id') == $note->staff_id){
                $tickets->updateNote($this->request->getPost('new_note'), $note->id);
                $this->session->setFlashdata('ticket_update', lang('Admin.tickets.noteUpdated'));
                return redirect()->to(current_url());
            }else{
                $error_msg = lang('Admin.tickets.invalidRequest');
            }
        }
        elseif ($this->request->getPost('do') == 'save_notes'){
            if($this->request->getPost('noteBook') == ''){
                $error_msg = lang('Admin.tickets.enterNote');
            }else{
                $tickets->addNote($ticket->id, $this->staff->getData('id'), $this->request->getPost('noteBook'));
                $this->session->setFlashdata('ticket_update', lang('Admin.tickets.notesSaved'));
                return redirect()->to(current_url());
            }
        }

        if($this->session->has('ticket_update')){
            $success_msg = $this->session->getFlashdata('ticket_update');
        }

        $messages = $tickets->getMessages($ticket->id);
        if(defined('HDZDEMO')){
            $ticket->email = '[Hidden in demo]';
        }
        return view('staff/ticket_view',[
            'error_msg' => isset($error_msg) ? $error_msg : null,
            'success_msg' => isset($success_msg) ? $success_msg : null,
            'ticket' => $ticket,
            'canned_response' => $tickets->getCannedList(),
            'message_result' => $messages['result'],
            'pager' => $messages['pager'],
            'departments_list' => Services::departments()->getAll(),
            'ticket_statuses' => $tickets->statusList(),
            'ticket_priorities' => $tickets->getPriorities(),
            'kb_selector' => Services::kb()->kb_article_selector(),
            'notes' => $tickets->getNotes($ticket->id)
        ]);
    }

    public function create()
    {
        $tickets = Services::tickets();
        if($this->request->getPost('do') == 'submit')
        {
            $validation = Services::validation();
            $validation->setRules([
                'email' => 'required|valid_email',
                'department' => 'required|is_natural_no_zero|is_not_unique[departments.id]',
                'priority' => 'required|is_natural_no_zero|is_not_unique[priority.id]',
                'status' => 'required|is_natural|in_list[' . implode(',', array_keys($tickets->statusList())) . ']',
                'subject' => 'required',
                'message' => 'required'
            ],[
                'email' => [
                    'required' => lang('Admin.error.enterValidEmail'),
                    'valid_email' => lang('Admin.error.enterValidEmail')
                ],
                'department' => [
                    'required' => lang('Admin.error.invalidDepartment'),
                    'is_natural_no_zero' => lang('Admin.error.invalidDepartment'),
                    'is_not_unique' => lang('Admin.error.invalidDepartment'),
                ],
                'priority' => [
                    'required' => lang('Admin.error.invalidPriority'),
                    'is_natural_no_zero' => lang('Admin.error.invalidPriority'),
                    'is_not_unique' => lang('Admin.error.invalidPriority'),
                ],
                'status' => [
                    'required' => lang('Admin.error.invalidStatus'),
                    'is_natural' => lang('Admin.error.invalidStatus'),
                    'in_list' => lang('Admin.error.invalidStatus'),
                ],
                'subject' => [
                    'required' => lang('Admin.error.enterSubject'),
                ],
                'message' => [
                    'required' => lang('Admin.error.enterMessage'),
                ]
            ]);
            if($this->settings->config('ticket_attachment')){
                $max_size = $this->settings->config('ticket_file_size')*1024;
                $allowed_extensions = unserialize($this->settings->config('ticket_file_type'));
                $allowed_extensions = implode(',', $allowed_extensions);
                $validation->setRule('attachment', 'attachment', 'ext_in[attachment,'.$allowed_extensions.']|max_size[attachment,'.$max_size.']',[
                    'ext_in' => lang('Admin.error.fileNotAllowed'),
                    'max_size' => lang_replace('Admin.error.fileBig', ['%size%' => number_to_size($max_size*1024, 2)])
                ]);
            }

            if($validation->withRequest($this->request)->run() == false) {
                $error_msg = $validation->listErrors();
            }elseif (defined('HDZDEMO')){
                $error_msg = 'This is not possible in demo version.';
            }else{
                $attachments = Services::attachments();
                if($this->settings->config('ticket_attachment')){
                    if($uploaded_files = $attachments->ticketUpload()){
                        $files = $uploaded_files;
                    }
                }
                $name = ($this->request->getPost('fullname') == '') ? $this->request->getPost('email') : $this->request->getPost('fullname');
                $client_id = $this->client->getClientID($name, $this->request->getPost('email'));
                $ticket_id = $tickets->createTicket($client_id, $this->request->getPost('subject'), $this->request->getPost('department'), $this->request->getPost('priority'));
                $message = $this->request->getPost('message').$this->staff->getData('signature');
                $message_id = $tickets->addMessage($ticket_id, $message, $this->staff->getData('id'));
                $tickets->updateTicket([
                    'last_replier' => $this->staff->getData('id'),
                    'status' => $this->request->getPost('status')
                ], $ticket_id);
                //File
                if(isset($files)){
                    $attachments->addTicketFiles($ticket_id, $message_id, $files);
                }

                $ticket = $tickets->getTicket(['id' => $ticket_id]);
                $tickets->replyTicketNotification($ticket, $message, (isset($files) ? $files : null));
                $this->session->setFlashdata('form_success','Ticket has been created and client was notified.');
                return redirect()->route('staff_ticket_view', [$ticket_id]);
            }
        }


        return view('staff/ticket_new',[
            'error_msg' => isset($error_msg) ? $error_msg : null,
            'success_msg' => isset($success_msg) ? $success_msg : null,
            'canned_response' => $tickets->getCannedList(),
            'departments_list' => Services::departments()->getAll(),
            'ticket_statuses' => $tickets->statusList(),
            'ticket_priorities' => $tickets->getPriorities(),
            'kb_selector' => Services::kb()->kb_article_selector(),
        ]);
    }

    public function cannedResponses()
    {
        $tickets = Services::tickets();
        if($this->request->getPost('do') == 'remove'){
            if(!$canned = $tickets->getCannedResponse($this->request->getPost('msgID'))){
                $error_msg = lang('Admin.error.invalidCannedResponse');
            }elseif(!$this->staff->getData('admin') && $canned->staff_id != $this->staff->getData('id')) {
                $error_msg = lang('Admin.error.invalidCannedResponse');
            }elseif (defined('HDZDEMO')){
                $error_msg = 'This is not possible in demo version.';
            }else{
                $tickets->deleteCanned($canned->id);
                $this->session->setFlashdata('canned_update','Canned response has been removed.');
                return redirect()->route('staff_canned');
            }
        }

        if($this->request->getGet('action') && is_numeric($this->request->getGet('msgID'))){
            if(!$canned = $tickets->getCannedResponse($this->request->getGet('msgID'))){
                $error_msg = lang('Admin.error.invalidCannedResponse');
            }elseif (defined('HDZDEMO')){
                $error_msg = 'This is not possible in demo version.';
            }else{
                $cannedModel = new CannedModel();
                switch ($this->request->getGet('action')){
                    case 'move_up':
                        if($canned->position > 1){
                            $cannedModel->protect(false);
                            $cannedModel->set('position', $canned->position)
                                ->where('position', ($canned->position-1))
                                ->update();
                            $cannedModel->protect(true);
                            $tickets->changeCannedPosition(($canned->position-1), $canned->id);
                        }
                        break;
                    case 'move_down':
                        if($canned->position < $tickets->lastCannedPosition()){
                            $cannedModel->protect(false);
                            $cannedModel->set('position', $canned->position)
                                ->where('position', ($canned->position+1))
                                ->update();
                            $cannedModel->protect(true);
                            $tickets->changeCannedPosition(($canned->position+1), $canned->id);
                        }
                        break;
                }
                return redirect()->route('staff_canned');
            }
        }
        if($this->session->has('canned_update')){
            $success_msg = $this->session->getFlashdata('canned_update');
        }
        return view('staff/canned_manage',[
            'cannedList' => $tickets->getCannedList(),
            'lastCannedPosition' => $tickets->lastCannedPosition(),
            'error_msg' => isset($error_msg) ? $error_msg : null,
            'success_msg' => isset($success_msg) ? $success_msg : null
        ]);
    }

    public function editCannedResponses($canned_id)
    {
        $tickets = Services::tickets();
        if(!$canned = $tickets->getCannedResponse($canned_id)){
            return redirect()->route('staff_canned');
        }
        if($this->request->getPost('do') == 'submit')
        {
            $validation = Services::validation();
            $validation->setRules([
                'title' => 'required',
                'message' => 'required'
            ],[
                'title' => [
                    'required' => lang('Admin.error.enterTitle'),
                ],
                'message' => [
                    'required' => lang('Admin.error.enterMessage')
                ]
            ]);
            if($validation->withRequest($this->request)->run() == false){
                $error_msg = $validation->listErrors();
            }elseif (defined('HDZDEMO')){
                $error_msg = 'This is not possible in demo version.';
            }else{
                $tickets->updateCanned([
                    'title' => esc($this->request->getPost('title')),
                    'message' => $this->request->getPost('message'),
                    'last_update' => time()
                ], $canned_id);
                $this->session->setFlashdata('canned_update','Canned response has been updated.');
                return redirect()->to(current_url());
            }
        }

        if($this->session->has('canned_update')){
            $success_msg = $this->session->getFlashdata('canned_update');
        }
        return view('staff/canned_form',[
            'error_msg' => isset($error_msg) ? $error_msg : null,
            'success_msg' => isset($success_msg) ? $success_msg : null,
            'canned' => $canned,
            'staff_canned' => ($canned->staff_id > 0 ? $this->staff->getRow(['id'=>$canned->staff_id],'fullname') : null)
        ]);
    }

    public function newCannedResponse()
    {
        if($this->request->getPost('do') == 'submit'){
            $validation = Services::validation();
            $validation->setRules([
                'title' => 'required',
                'message' => 'required'
            ],[
                'title' => [
                    'required' => lang('Admin.error.enterTitle'),
                ],
                'message' => [
                    'required' => lang('Admin.error.enterMessage')
                ]
            ]);
            if($validation->withRequest($this->request)->run() == false){
                $error_msg = $validation->listErrors();
            }elseif (defined('HDZDEMO')){
                $error_msg = 'This is not possible in demo version.';
            }else{
                $tickets = Services::tickets();
                $tickets->insertCanned($this->request->getPost('title'), $this->request->getPost('message'));
                $this->session->setFlashdata('canned_update', 'Canned response has been inserted.');
                return redirect()->route('staff_canned');
            }
        }

        return view('staff/canned_form',[
            'error_msg' => isset($error_msg) ? $error_msg : null,
            'success_msg' => $this->session->has('canned_update') ? $this->session->getFlashdata('canned_update') : null,
        ]);
    }
}