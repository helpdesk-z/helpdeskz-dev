<?php
/**
 * @package EvolutionScript
 * @author: EvolutionScript S.A.C.
 * @Copyright (c) 2010 - 2020, EvolutionScript.com
 * @link http://www.evolutionscript.com
 */

namespace App\Controllers\Staff;


use App\Controllers\BaseController;
use App\Libraries\Emails;
use Config\Services;

class Settings extends BaseController
{
    public function general()
    {
        if($this->staff->getData('admin') != 1){
            return redirect()->route('staff_dashboard');
        }

        if($this->request->getMethod() == 'post'){
            if (defined('HDZDEMO')) {
                $error_msg = 'This is not possible in demo version.';
            }else{
                if($this->request->getPost('action') == 'deleteLogo'){
                    if($this->settings->config('logo') != ''){
                        @unlink(FCPATH.'upload/'.$this->settings->config('logo'));
                    }
                    $this->settings->save('logo','');
                    $this->session->setFlashdata('logo_success',lang('Admin.settings.logoRestored'));
                    return redirect()->to(current_url());
                }
                elseif ($this->request->getPost('action') == 'uploadLogo') {
                    $validation = Services::validation();
                    $validation->setRule('logo', 'logo', 'uploaded[logo]|is_image[logo]|max_size[logo,' . max_file_size() . ']', [
                        'uploaded' => lang('Admin.error.selectLogo'),
                        'is_image' => lang('Admin.error.selectLogo'),
                        'max_size' => lang_replace('Admin.error.logoSize', ['%size%' => number_to_size(max_file_size() * 1024, 2)])
                    ]);
                    if ($validation->withRequest($this->request)->run() == false) {
                        $logo_error = $validation->listErrors();
                    } elseif (!$file = $this->request->getFile('logo')) {
                        $logo_error = lang('Admin.error.uploadingLogo');
                    } elseif ($file->hasMoved()) {
                        $logo_error = lang('Admin.error.uploadingLogo');
                    } else {
                        $newName = $file->getRandomName();
                        $imgPath = FCPATH . 'upload';
                        $file->move($imgPath, $newName);
                        $this->settings->save('logo', $newName);
                        $this->session->setFlashdata('logo_success', lang('Admin.settings.logoChanged'));
                        return redirect()->to(current_url());
                    }
                }
                elseif ($this->request->getPost('action') == 'update_maintenance'){
                    $validation = Services::validation();
                    $validation->setRule('maintenance',lang('Admin.form.status'), 'required|in_list[0,1]');
                    if($validation->withRequest($this->request)->run() == false){
                        $maintenance_error = $validation->listErrors();
                    }else{
                        $this->settings->save([
                            'maintenance' => $this->request->getPost('maintenance'),
                            'maintenance_message' => $this->request->getPost('maintenance_message'),
                        ]);
                        $this->session->setFlashdata('maintenance_success',lang('Admin.settings.updated'));
                        return redirect()->to(current_url());
                    }
                }
                elseif($this->request->getPost('action') == 'updateConfig'){
                    $validation = Services::validation();
                    $validation->setRules([
                        'site_name' => 'required',
                        'windows_title' => 'required',
                        'page_size' => 'required|is_natural_no_zero',
                        'date_format' => 'required',
                        'timezone' => 'in_list['.implode(',', timezone_identifiers_list()).']'
                    ],[
                        'site_name' => [
                            'required' => lang('Admin.error.enterSiteName')
                        ],
                        'windows_title' => [
                            'required' => lang('Admin.error.enterWindowTitle')
                        ],
                        'page_size' => [
                            'required' => lang('Admin.error.enterPageSize'),
                            'is_natural_no_zero' => lang('Admin.error.enterPageSize'),
                        ],
                        'date_format' => [
                            'required' => lang('Admin.error.enterDateFormat')
                        ],
                        'timezone' => [
                            'in_list' => lang('Admin.error.selectTimezone')
                        ]
                    ]);

                    if($validation->withRequest($this->request)->run() == false){
                        $error_msg = $validation->listErrors();
                    }else{
                        $this->settings->save([
                            'site_name' => esc($this->request->getPost('site_name')),
                            'windows_title' => esc($this->request->getPost('windows_title')),
                            'page_size' => $this->request->getPost('page_size'),
                            'date_format' => esc($this->request->getPost('date_format')),
                            'timezone' => $this->request->getPost('timezone'),
                        ]);
                        $this->session->setFlashdata('form_success',lang('Admin.settings.updated'));
                        return redirect()->to(current_url());
                    }
                }
            }
        }


        return view('staff/settings_general',[
            'logo_error' => isset($logo_error) ? $logo_error : null,
            'logo_success' => $this->session->has('logo_success') ? $this->session->getFlashdata('logo_success') : null,
            'error_msg' => isset($error_msg) ? $error_msg : null,
            'success_msg' => $this->session->has('form_success') ? $this->session->getFlashdata('form_success') : null,
            'maintenance_error' => isset($maintenance_error) ? $maintenance_error : null,
            'maintenance_success' => $this->session->has('maintenance_success') ? $this->session->getFlashdata('maintenance_success') : null,
        ]);
    }

    public function security()
    {
        if($this->staff->getData('admin') != 1){
            return redirect()->route('staff_dashboard');
        }

        if($this->request->getPost('do') == 'submit'){
            $validation = Services::validation();
            $validation->setRule('recaptcha','reCAPTCHA status','required|in_list[0,1]',[
                'required' => lang('Admin.error.selectCaptchaStatus'),
                'in_list' => lang('Admin.error.selectCaptchaStatus'),
            ]);
            if($this->request->getPost('recaptcha') == 1){
                $validation->setRule('recaptcha_sitekey','reCAPTCHA site key','required',[
                    'required' => lang('Admin.error.enterSiteKey'),
                ]);
                $validation->setRule('recaptcha_privatekey','reCAPTCHA private key','required',[
                    'required' => lang('Admin.error.enterPrivateKey'),
                ]);
            }
            $validation->setRule('login_attempt','Maximum number of login attempts','required|is_natural_no_zero',[
                'required' => lang('Admin.error.enterMaxAttempts'),
                'is_natural_no_zero' => lang('Admin.error.enterMaxAttempts')
            ]);
            $validation->setRule('login_attempt_minutes','Minutes of IP locking','required|is_natural_no_zero',[
                'required' => lang('Admin.error.enterMinutesIpLocking'),
                'is_natural_no_zero' => lang('Admin.error.enterMinutesIpLocking')
            ]);

            if($validation->withRequest($this->request)->run() == false){
                $error_msg = $validation->listErrors();
            }elseif (defined('HDZDEMO')){
                $error_msg = 'This is not possible in demo version.';
            }else{
                $this->settings->save([
                    'recaptcha' => $this->request->getPost('recaptcha'),
                    'recaptcha_sitekey' => esc($this->request->getPost('recaptcha_sitekey')),
                    'recaptcha_privatekey' => esc($this->request->getPost('recaptcha_privatekey')),
                    'login_attempt' => $this->request->getPost('login_attempt'),
                    'login_attempt_minutes' => $this->request->getPost('login_attempt_minutes'),
                ]);
                $this->session->setFlashdata('form_success',lang('Admin.settings.updated'));
                return redirect()->to(current_url());
            }
        }

        return view('staff/settings_security',[
            'error_msg' => isset($error_msg) ? $error_msg : null,
            'success_msg' => $this->session->has('form_success') ? $this->session->getFlashdata('form_success') : null,
        ]);
    }

    public function tickets()
    {
        if($this->staff->getData('admin') != 1){
            return redirect()->route('staff_dashboard');
        }

        if($this->request->getPost('do') == 'submit'){
            $validation = Services::validation();
            $validation->setRule('reply_order',lang('Admin.settings.displayOrderReplies'),'required|in_list[asc,desc]');
            $validation->setRule('tickets_page',lang('Admin.settings.ticketsPerPage'),'required|is_natural_no_zero');
            $validation->setRule('tickets_replies',lang('Admin.settings.repliesPerPage'),'required|is_natural_no_zero');
            $validation->setRule('overdue_time',lang('Admin.settings.defaultDeadline'),'required|is_natural_no_zero');
            $validation->setRule('ticket_autoclose',lang('Admin.settings.defaultAutoClose'),'required|is_natural_no_zero');
            $validation->setRule('ticket_attachment',lang('Admin.settings.allowAttachments'),'required|in_list[0,1]',[
                'in_list' => 'Select a valid option of Allow attachments.'
            ]);
            if($this->request->getPost('ticket_attachment') == 1){
                $validation->setRule('ticket_attachment_number',lang('Admin.settings.numberAttachments'),'required|is_natural');
                $validation->setRule('ticket_file_size', lang('Admin.settings.maxUploadSize'), 'required|greater_than[0]');
                $validation->setRule('ticket_file_type',lang('Admin.settings.allowedFileTypes'),'required');
            }

            if($validation->withRequest($this->request)->run() == false){
                $error_msg = $validation->listErrors();
            }elseif (defined('HDZDEMO')){
                $error_msg = 'This is not possible in demo version.';
            }else{

                $file_types = explode(',', $this->request->getPost('ticket_file_type'));
                $file_types = array_map(function ($e){
                    $e = trim($e);
                    if($e != ''){
                        return $e;
                    }else{
                        return null;
                    }
                }, $file_types);
                $file_types = array_filter($file_types, function ($e){
                    return (trim($e) != '');
                });
                $this->settings->save([
                    'reply_order' => $this->request->getPost('reply_order'),
                    'tickets_page' => $this->request->getPost('tickets_page'),
                    'tickets_replies' => $this->request->getPost('tickets_replies'),
                    'overdue_time' => $this->request->getPost('overdue_time'),
                    'ticket_autoclose' => $this->request->getPost('ticket_autoclose'),
                    'ticket_attachment' => $this->request->getPost('ticket_attachment'),
                    'ticket_attachment_number' => ($this->request->getPost('ticket_attachment') == '1' ? $this->request->getPost('ticket_attachment_number') : $this->settings->config('ticket_attachment_number')),
                    'ticket_file_size' => ($this->request->getPost('ticket_attachment') == '1' ? $this->request->getPost('ticket_file_size') : $this->settings->config('ticket_file_size')),
                    'ticket_file_type' => ($this->request->getPost('ticket_attachment') == '1' ? serialize($file_types) : $this->settings->config('ticket_file_type')),
                ]);
                $this->session->remove('cron');
                $this->session->setFlashdata('form_success',lang('Admin.settings.updated'));
                return redirect()->to(current_url());
            }
        }

        return view('staff/settings_tickets',[
            'error_msg' => isset($error_msg) ? $error_msg : null,
            'success_msg' => $this->session->has('form_success') ? $this->session->getFlashdata('form_success') : null,
        ]);
    }

    public function kb()
    {
        if($this->staff->getData('admin') != 1){
            return redirect()->route('staff_dashboard');
        }

        if($this->request->getPost('do') == 'submit'){
            $validation = Services::validation();
            $validation->setRule('kb_articles',lang('Admin.settings.articlesUnderCategory'),'required|is_natural_no_zero');
            $validation->setRule('kb_maxchar',lang('Admin.settings.charLimitArticlePreview'),'required|is_natural_no_zero');
            $validation->setRule('kb_popular',lang('Admin.settings.popularArticles'),'required|is_natural_no_zero');
            $validation->setRule('kb_latest',lang('Admin.settings.newestArticles'),'required|is_natural_no_zero');

            if($validation->withRequest($this->request)->run() == false){
                $error_msg = $validation->listErrors();
            }elseif (defined('HDZDEMO')){
                $error_msg = 'This is not possible in demo version.';
            }else{

                $this->settings->save([
                    'kb_articles' => $this->request->getPost('kb_articles'),
                    'kb_maxchar' => $this->request->getPost('kb_maxchar'),
                    'kb_popular' => $this->request->getPost('kb_popular'),
                    'kb_latest' => $this->request->getPost('kb_latest'),
                ]);
                $this->session->setFlashdata('form_success',lang('Admin.settings.updated'));
                return redirect()->to(current_url());
            }
        }
        return view('staff/settings_kb',[
            'error_msg' => isset($error_msg) ? $error_msg : null,
            'success_msg' => $this->session->has('form_success') ? $this->session->getFlashdata('form_success') : null,
        ]);
    }

    public function emailTemplates()
    {
        if($this->staff->getData('admin') != 1){
            return redirect()->route('staff_dashboard');
        }

        $emailsLib = new Emails();
        if($this->request->getPost('action') == 'change_status'){
            if (defined('HDZDEMO')) {
                $error_msg = 'This is not possible in demo version.';
            }else{
                if($template = $emailsLib->getTemplate($this->request->getPost('email_id'))){
                    if($template->status != 2){
                        $emailsLib->updateTemplate([
                            'status' => ($template->status == 1 ? 0 : 1)
                        ], $template->id);
                    }
                }
                return redirect()->to(current_url());
            }
        }
        return view('staff/email_template',[
            'error_msg' => isset($error_msg) ? $error_msg : null,
            'emailsList' => $emailsLib->getAllTemplates()
        ]);
    }

    public function emailTemplatesEdit($email_id)
    {
        if($this->staff->getData('admin') != 1){
            return redirect()->route('staff_dashboard');
        }

        $emailLib = new Emails();
        if(!$template = $emailLib->getTemplate($email_id)){
            return redirect()->route('staff_email_templates');
        }
        if($this->request->getPost('do') == 'submit'){
            $validation = Services::validation();
            $validation->setRule('subject',lang('Admin.form.subject'),'required');
            $validation->setRule('message',lang('Admin.form.message'),'required');
            if($validation->withRequest($this->request)->run() == false){
                $error_msg = $validation->listErrors();
            }elseif (defined('HDZDEMO')){
                $error_msg = 'This is not possible in demo version.';
            }else{
                $emailLib->updateTemplate([
                    'subject' => $this->request->getPost('subject'),
                    'message' => $this->request->getPost('message'),
                    'last_update' => time()
                ], $template->id);
                $this->session->setFlashdata('form_success',lang('Admin.settings.emailTemplateUpdated'));
                return redirect()->to(current_url());
            }
        }
        return view('staff/email_template_form',[
            'error_msg' => isset($error_msg) ? $error_msg : null,
            'success_msg' => $this->session->has('form_success') ? $this->session->getFlashdata('form_success') : null,
            'template' => $template
        ]);
    }

    public function emails()
    {
        if($this->staff->getData('admin') != 1){
            return redirect()->route('staff_dashboard');
        }

        $emailsLib = new Emails();
        if($this->request->getMethod() == 'post'){
            $error_msg = 'This is not possible in demo version.';
        }else{
            if($this->request->getPost('action') == 'set_default')
            {
                $emailsLib->set_default($this->request->getPost('email_id'));
                $this->session->setFlashdata('form_success',lang('Admin.settings.defaultEmailChanged'));
                return redirect()->to(current_url());
            }elseif ($this->request->getPost('action') == 'remove'){
                $emailsLib->remove_email($this->request->getPost('email_id'));
                $this->session->setFlashdata('form_success',lang('Admin.settings.emailRemoved'));
                return redirect()->to(current_url());
            }
        }

        return view('staff/email_addresses',[
            'error_msg' => isset($error_msg) ? $error_msg : null,
            'success_msg' => $this->session->has('form_success') ? $this->session->getFlashdata('form_success') : null,
            'emailsList' => $emailsLib->getAll()
        ]);
    }

    public function emailsCreate()
    {
        if($this->staff->getData('admin') != 1){
            return redirect()->route('staff_dashboard');
        }

        $emailsLib = new Emails();
        if($this->request->getPost('do') == 'submit')
        {
            $validation = Services::validation();
            $validation->setRule('email',lang('Admin.form.email'),'required|valid_email|is_unique[emails.email]',[
                'is_unique' => lang('Admin.error.emailExists')
            ]);
            $validation->setRule('name',lang('Admin.form.emailName'),'required');
            $validation->setRule('department_id',lang('Admin.form.department'),'required|is_natural_no_zero',[
                'required' => lang('Admin.error.invalidDepartment'),
                'is_natural_no_zero' => lang('Admin.error.invalidDepartment')
            ]);
            $validation->setRule('outgoing_type','Outgoing type','required|in_list[php,smtp]',[
                'required' => lang('Admin.error.selectOutgoing'),
                'in_list' => lang('Admin.error.selectOutgoing')
            ]);
            if($this->request->getPost('outgoing_type') == 'smtp')
            {
                $validation->setRule('smtp_host',lang('Admin.settings.smtpHost'),'required');
                $validation->setRule('smtp_port',lang('Admin.settings.smtpPort'),'required|is_natural_no_zero');
                $validation->setRule('smtp_encryption',lang('Admin.settings.smtpEncryption'),'in_list[ssl,tls]');
            }
            if($this->request->getPost('incoming_type') != '' && $this->request->getPost('incoming_type') != 'pipe')
            {
                $validation->setRule('incoming_type','Incoming type','in_list[pop,imap]',[
                    'in_list' => lang('Admin.error.selectIncoming')
                ]);
                $validation->setRule('imap_host',lang('Admin.settings.incomingHost'),'required');
                $validation->setRule('imap_port',lang('Admin.settings.incomingPort'),'required|is_natural_no_zero');
                $validation->setRule('imap_username',lang('Admin.settings.incomingUsername'),'required');
                $validation->setRule('imap_password',lang('Admin.settings.incomingPassword'),'required');
            }
            if($validation->withRequest($this->request)->run() == false){
                $error_msg = $validation->listErrors();
            }elseif (defined('HDZDEMO')){
                $error_msg = 'This is not possible in demo version.';
            }elseif($emailsLib->departmentInUse($this->request->getPost('department_id'))){
                $error_msg = lang('Admin.error.emailTakenDepartment');
            }else{
                $emailsLib->addEmail();
                $this->session->setFlashdata('form_success',lang('Admin.settings.emailCreated'));
                return redirect()->to(current_url());
            }
        }
        return view('staff/email_addresses_form',[
            'error_msg' => isset($error_msg) ? $error_msg : null,
            'success_msg' => $this->session->has('form_success') ? $this->session->getFlashdata('form_success') : null,
            'departments' => Services::departments()->getAll()
        ]);
    }

    public function emailsEdit($email_id)
    {
        if($this->staff->getData('admin') != 1){
            return redirect()->route('staff_dashboard');
        }

        $emailsLib = new Emails();
        if(!$email = $emailsLib->getByID($email_id)){
            return redirect()->route('staff_emails');
        }
        if($this->request->getPost('do') == 'submit')
        {
            $validation = Services::validation();
            if($this->request->getPost('email') != $email->email)
            {
                $validation->setRule('email',lang('Admin.form.email'),'required|valid_email|is_unique[emails.email]',[
                    'is_unique' => lang('Admin.error.emailExists')
                ]);
            }
            $validation->setRule('name',lang('Admin.form.emailName'),'required');
            $validation->setRule('department_id',lang('Admin.form.department'),'required|is_natural_no_zero',[
                'required' => lang('Admin.error.invalidDepartment'),
                'is_natural_no_zero' => lang('Admin.error.invalidDepartment')
            ]);
            $validation->setRule('outgoing_type','Outgoing type','required|in_list[php,smtp]',[
                'required' => lang('Admin.error.selectOutgoing'),
                'in_list' => lang('Admin.error.selectOutgoing')
            ]);
            if($this->request->getPost('outgoing_type') == 'smtp')
            {
                $validation->setRule('smtp_host',lang('Admin.settings.smtpHost'),'required');
                $validation->setRule('smtp_port',lang('Admin.settings.smtpPort'),'required|is_natural_no_zero');
                $validation->setRule('smtp_encryption',lang('Admin.settings.smtpEncryption'),'in_list[ssl,tls]');
            }

            if($this->request->getPost('incoming_type') != '' && $this->request->getPost('incoming_type') != 'pipe')
            {
                $validation->setRule('incoming_type','Incoming type','in_list[pop,imap]',[
                    'in_list' => lang('Admin.error.selectIncoming')
                ]);
                $validation->setRule('imap_host',lang('Admin.settings.incomingHost'),'required');
                $validation->setRule('imap_port',lang('Admin.settings.incomingPort'),'required|is_natural_no_zero');
                $validation->setRule('imap_username',lang('Admin.settings.incomingUsername'),'required');
                $validation->setRule('imap_password',lang('Admin.settings.incomingPassword'),'required');
            }
            if($validation->withRequest($this->request)->run() == false){
                $error_msg = $validation->listErrors();
            }elseif (defined('HDZDEMO')){
                $error_msg = 'This is not possible in demo version.';
            }elseif($this->request->getPost('department_id') != $email->department_id  && $emailsLib->departmentInUse($this->request->getPost('department_id'))){
                $error_msg = lang('Admin.error.emailTakenDepartment');
            }else{
                $emailsLib->updateEmail($email->id);
                $this->session->setFlashdata('form_success',lang('Admin.settings.emailUpdated'));
                return redirect()->to(current_url());
            }
        }
        return view('staff/email_addresses_form',[
            'error_msg' => isset($error_msg) ? $error_msg : null,
            'success_msg' => $this->session->has('form_success') ? $this->session->getFlashdata('form_success') : null,
            'departments' => Services::departments()->getAll(),
            'email' => $email
        ]);
    }

    /*
     * -----------------------------------------
     * API Configuraiton
     * -----------------------------------------
     */

    public function api()
    {
        if($this->staff->getData('admin') != 1){
            return redirect()->route('staff_dashboard');
        }
        $api = Services::api();

        if ($this->request->getPost('do') == 'remove'){
            $validation = Services::validation();
            $validation->setRule('api_id', 'api_id','required|is_natural_no_zero');
            if($validation->withRequest($this->request)->run() == false){
                $error_msg = lang('Api.error.invalidID');
            }else{
                $api->delete($this->request->getPost('api_id'));
                $this->session->setFlashdata('form_success',lang('Api.apiRemoved'));
                return redirect()->to(current_url());
            }
        }

        return view('staff/api',[
            'error_msg' => isset($error_msg) ? $error_msg : null,
            'success_msg' => $this->session->has('form_success') ? $this->session->getFlashdata('form_success') : null,
            'api_list' => $api->getList(),
        ]);
    }

    public function apiCreate()
    {
        if($this->staff->getData('admin') != 1){
            return redirect()->route('staff_dashboard');
        }


        $api = Services::api();
        if($this->request->getPost('do') == 'submit')
        {
            $validation = Services::validation();
            $validation->setRule('name','name','required',[
                'required' => lang('Api.error.apiName')
            ]);
            $validation->setRule('active','active', 'required|in_list[0,1]',[
                'required' => lang('Api.error.invalidStatus.'),
                'in_list' => lang('Api.error.invalidStatus.')
            ]);
            if($validation->withRequest($this->request)->run() == false) {
                $error_msg = $validation->listErrors();
            }elseif (defined('HDZDEMO')) {
                $error_msg = 'This is not possible in demo version.';
            }else{
                $permissions = array();
                foreach ($api->permissionList() as $item => $data)
                {
                    foreach ($data['options'] as $key => $v){
                        if(isset($this->request->getPost($item)[$key])){
                            $permissions[$item][$key] = 1;
                        }
                    }
                }
                $api_id = $api->create($this->request->getPost('name'), $permissions, $this->request->getPost('active'));
                $this->session->setFlashdata('form_success',lang('Api.successCreation'));
                return redirect()->route('staff_api_edit',[$api_id]);

            }
        }
        return view('staff/api_form',[
            'error_msg' => isset($error_msg) ? $error_msg : null,
            'success_msg' => $this->session->has('form_success') ? $this->session->getFlashdata('form_success') : null,
            'api_permissions' => $api->permissionList(),
        ]);
    }

    public function apiEdit($api_id)
    {
        if($this->staff->getData('admin') != 1){
            return redirect()->route('staff_dashboard');
        }
        $api = Services::api();
        if(!$api_info = $api->getRow(['id'=>$api_id])){
            return redirect()->route('staff_api');
        }

        if($this->request->getPost('do') == 'submit')
        {
            $validation = Services::validation();
            $validation->setRule('name','name','required',[
                'required' => lang('Api.error.apiName')
            ]);
            $validation->setRule('active','active', 'required|in_list[0,1]',[
                'required' => lang('Api.error.invalidStatus.'),
                'in_list' => lang('Api.error.invalidStatus.')
            ]);
            if($validation->withRequest($this->request)->run() == false){
                $error_msg = $validation->listErrors();
            }elseif (defined('HDZDEMO')) {
                $error_msg = 'This is not possible in demo version.';
            }else{
                $permissions = array();
                foreach ($api->permissionList() as $item => $data)
                {
                    foreach ($data['options'] as $key => $v){
                        if(isset($this->request->getPost($item)[$key])){
                            $permissions[$item][$key] = 1;
                        }
                    }
                }
                $api->update([
                    'name' => esc($this->request->getPost('name')),
                    'permissions' => serialize($permissions),
                    'active' => $this->request->getPost('active')
                ], $api_info->id);
                $this->session->setFlashdata('form_success',lang('Api.successUpdate'));
                return redirect()->to(current_url());

            }
        }

        return view('staff/api_form',[
            'error_msg' => isset($error_msg) ? $error_msg : null,
            'success_msg' => $this->session->has('form_success') ? $this->session->getFlashdata('form_success') : null,
            'api_permissions' => $api->permissionList(),
            'api_info' => $api_info
        ]);
    }
}