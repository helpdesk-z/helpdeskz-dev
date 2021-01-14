<?php
/**
 * @package EvolutionScript
 * @author: EvolutionScript S.A.C.
 * @Copyright (c) 2010 - 2020, EvolutionScript.com
 * @link http://www.evolutionscript.com
 */

namespace App\Libraries;


use App\Models\EmailTemplate;
use Config\Services;

class Emails
{
    protected $emailModel;
    public function __construct()
    {
        $this->emailModel = new \App\Models\Emails();
    }
    public function getAll()
    {
        $q = $this->emailModel->orderBy('default','desc')
            ->orderBy('created','desc')
            ->get();
        if($q->resultID->num_rows == 0){
            return null;
        }
        $r = $q->getResult();
        $q->freeResult();
        return $r;
    }

    public function getFetcher()
    {
        $q = $this->emailModel->where('incoming_type', 'imap')
            ->orWhere('incoming_type', 'pop')
            ->get();
        if($q->resultID->num_rows == 0){
            return null;
        }
        $r = $q->getResult();
        $q->freeResult();
        return $r;
    }

    public function getDefault()
    {
        return $this->getRow(['default' => 1]);
    }

    public function getByID($id)
    {
        if($data = $this->emailModel->find($id)){
            return $data;
        }
        return null;
    }

    public function getRow($where=array())
    {
        $q = $this->emailModel->where($where)->get(1);
        if($q->resultID->num_rows == 0){
            return null;
        }
        return $q->getRow();
    }

    public function getByDepartment($id)
    {
        return $this->getRow(['department_id' => $id]);
    }

    public function set_default($id)
    {
        $count = $this->emailModel->where('id', $id)
            ->countAllResults();
        if($count == 0){
            return false;
        }
        $this->emailModel->protect(false);
        $this->emailModel->where('default', '1')
            ->set('default', 0)
            ->update();
        $this->emailModel->update($id, [
            'default' => '1'
        ]);
        $this->emailModel->protect(true);
    }

    public function remove_email($id)
    {
        $this->emailModel->delete($id);
    }

    public function departmentInUse($id)
    {
        $q = $this->emailModel->where('department_id', $id)
            ->countAllResults();
        return ($q > 0 ? true : false);
    }

    public function addEmail()
    {
        $request = Services::request();
        $this->emailModel->protect(false);
        $this->emailModel->insert([
            'name' => $request->getPost('name'),
            'email' => $request->getPost('email'),
            'department_id' => $request->getPost('department_id'),
            'created' => time(),
            'outgoing_type' => $request->getPost('outgoing_type'),
            'smtp_host' => $request->getPost('smtp_host'),
            'smtp_port' => (is_numeric($request->getPost('smtp_port')) ? $request->getPost('smtp_port') : ''),
            'smtp_encryption' => $request->getPost('smtp_encryption'),
            'smtp_username' => $request->getPost('smtp_username'),
            'smtp_password' => $request->getPost('smtp_password'),
            'incoming_type' => $request->getPost('incoming_type'),
            'imap_host' => $request->getPost('imap_host'),
            'imap_port' => (is_numeric($request->getPost('imap_port')) ? $request->getPost('imap_port') : ''),
            'imap_username' => $request->getPost('imap_username'),
            'imap_password' => $request->getPost('imap_password'),
        ]);
        $this->emailModel->protect(true);
        return $this->emailModel->getInsertID();
    }

    public function updateEmail($id)
    {
        $request = Services::request();
        $this->emailModel->protect(false);
        $this->emailModel->update($id, [
            'name' => $request->getPost('name'),
            'email' => $request->getPost('email'),
            'department_id' => $request->getPost('department_id'),
            'last_update' => time(),
            'outgoing_type' => $request->getPost('outgoing_type'),
            'smtp_host' => $request->getPost('smtp_host'),
            'smtp_port' => (is_numeric($request->getPost('smtp_port')) ? $request->getPost('smtp_port') : ''),
            'smtp_encryption' => $request->getPost('smtp_encryption'),
            'smtp_username' => $request->getPost('smtp_username'),
            'smtp_password' => $request->getPost('smtp_password'),
            'incoming_type' => $request->getPost('incoming_type'),
            'imap_host' => $request->getPost('imap_host'),
            'imap_port' => (is_numeric($request->getPost('imap_port')) ? $request->getPost('imap_port') : ''),
            'imap_username' => $request->getPost('imap_username'),
            'imap_password' => $request->getPost('imap_password'),
        ]);
        $this->emailModel->protect(true);
        return true;
    }


    public function sendFromTemplate($template_id, $data, $user_email, $department_id=0, $attachments='')
    {
        if($department_id > 0){
            if(!$email = $this->getByDepartment($department_id)){
                if(!$email = $this->getDefault()){
                    return false;
                }
            }
        }else{
            if(!$email = $this->getDefault()){
                return false;
            }
        }
        $settings = Services::settings();
        $data['%company_name%'] = $settings->config('site_name');
        $data['%support_url%'] = site_url();
        if(!$template = $this->getTemplate($template_id)){
            return false;
        }
        if($template->status == 0){
            return false;
        }
        $subject = str_replace(array_keys($data), array_values($data), $template->subject);
        $message = str_replace(array_keys($data), array_values($data), $template->message);


        $emailClass = Services::email();
        if($email->outgoing_type == 'smtp'){
            $config['protocol'] = 'smtp';
            $config['SMTPHost'] = $email->smtp_host;
            $config['SMTPUser'] = $email->smtp_username;
            $config['SMTPPass'] = $email->smtp_password;
            $config['SMTPPort'] = $email->smtp_port;
            $config['SMTPCrypto'] = $email->smtp_encryption;
        }
        $config['userAgent'] = 'HelpDeskZ';
        $config['newline'] = "\r\n";
        $config['mailType'] = 'html';
        $emailClass->initialize($config);
        $emailClass->setFrom($email->email, $email->name);
        $emailClass->setTo($user_email);
        $emailClass->setSubject($subject);
        $emailClass->setMessage($message);
        if(is_array($attachments) && count($attachments) > 0){
            foreach ($attachments as $file){
                $file_content = file_get_contents($file['path']);
                $emailClass->attach($file_content, 'attachment', $file['name'], $file['file_type']);
            }
        }
        if(!$emailClass->send())
        {
            log_message('error', 'Error sending email to '.$user_email);
        }
        return true;
    }

    public function getAllTemplates()
    {
        $emailTemplate = new EmailTemplate();
        $q = $emailTemplate->orderBy('position','asc')
            ->get();
        if($q->resultID->num_rows == 0){
            return null;
        }
        $r = $q->getResult();
        $q->freeResult();
        return $r;
    }

    public function getTemplate($id)
    {
        $emailTemplate = new EmailTemplate();
        if($template = $emailTemplate->find($id)){
            return $template;
        }
        return null;
    }

    public function updateTemplate($data, $id)
    {
        $emailTemplate = new EmailTemplate();
        $emailTemplate->protect(false);
        $emailTemplate->update($id, $data);
        $emailTemplate->protect(true);
    }
}