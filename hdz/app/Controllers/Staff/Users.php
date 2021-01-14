<?php
/**
 * @package EvolutionScript
 * @author: EvolutionScript S.A.C.
 * @Copyright (c) 2010 - 2020, EvolutionScript.com
 * @link http://www.evolutionscript.com
 */

namespace App\Controllers\Staff;


use App\Controllers\BaseController;
use Config\Services;

class Users extends BaseController
{
    public function manage()
    {
        if($this->staff->getData('admin') != 1){
            return redirect()->route('staff_dashboard');
        }

        if($this->request->getPost('do') == 'remove'){
            $validation = Services::validation();
            $validation->setRule('user_id',lang('Admin.form.user'), 'required|is_natural_no_zero');
            if($validation->withRequest($this->request)->run() == false){
                $error_msg = $validation->listErrors();
            }elseif (defined('HDZDEMO')){
                $error_msg = 'This is not possible in demo version.';
            }else{
                $this->client->deleteAccount($this->request->getPost('user_id'));
                $this->session->setFlashdata('form_success',lang('Admin.users.userRemoved'));
                return redirect()->to(current_url());
            }
        }
        $pager = $this->client->manage();
        return view('staff/users',[
            'error_msg' => isset($error_msg) ? $error_msg : null,
            'success_msg' => $this->session->has('form_success') ? $this->session->getFlashdata('form_success') : null,
            'users_list' => $pager['result'],
            'pager' => $pager['pager']
        ]);
    }

    public function newAccount()
    {
        if($this->staff->getData('admin') != 1){
            return redirect()->route('staff_dashboard');
        }

        if($this->request->getPost('do') == 'submit')
        {
            $validation = Services::validation();
            $validation->setRule('fullname', lang('Admin.form.fullName'),'required',[
                'required' => lang('Admin.error.enterFullName')
            ]);
            $validation->setRule('email','Email','required|valid_email|is_unique[users.email]',[
                'required' => lang('Admin.error.enterValidEmail'),
                'valid_email' => lang('Admin.error.enterValidEmail'),
                'is_unique' => lang('Admin.error.emailTaken'),
            ]);
            $validation->setRule('password','Password','required|min_length[6]',[
                'required' => lang('Admin.error.enterPassword'),
                'min_length' => lang('Admin.error.enterPassword')
            ]);
            $validation->setRule('status','Status','required|in_list[0,1]',[
                'required' => lang('Admin.error.invalidStatus'),
                'in_list' => lang('Admin.error.invalidStatus'),
            ]);
            if($validation->withRequest($this->request)->run() == false){
                $error_msg = $validation->listErrors();
            }elseif (defined('HDZDEMO')){
                $error_msg = 'This is not possible in demo version.';
            }else{
                $client_id = $this->client->createAccount($this->request->getPost('fullname'), $this->request->getPost('email'), $this->request->getPost('password'), ($this->request->getPost('notify') ? true : false));
                if($this->request->getPost('status') == '0'){
                    $this->client->update(['status' => 0], $client_id);
                }
                $this->session->setFlashdata('form_success', lang('Admin.users.accountCreated'));
                return redirect()->to(current_url());
            }
        }
        return view('staff/users_form',[
            'error_msg' => isset($error_msg) ? $error_msg : null,
            'success_msg' => $this->session->has('form_success') ? $this->session->getFlashdata('form_success') : null,
        ]);
    }

    public function editAccount($user_id)
    {
        if($this->staff->getData('admin') != 1){
            return redirect()->route('staff_dashboard');
        }

        if(!$user = $this->client->getRow(['id' => $user_id])){
            return redirect()->route('staff_users');
        }
        if($this->request->getPost('do') == 'submit')
        {
            $validation = Services::validation();
            $validation->setRule('fullname', lang('Admin.form.fullName'),'required',[
                'required' => lang('Admin.error.enterFullName')
            ]);
            if($user->email != $this->request->getPost('email')){
                $validation->setRule('email','Email','required|valid_email|is_unique[users.email]',[
                    'required' => lang('Admin.error.enterValidEmail'),
                    'valid_email' => lang('Admin.error.enterValidEmail'),
                    'is_unique' => lang('Admin.error.emailTaken'),
                ]);
            }
            if($this->request->getPost('password')){
                $validation->setRule('password','Password','required|min_length[6]',[
                    'required' => lang('Admin.error.enterPassword'),
                    'min_length' => lang('Admin.error.enterPassword')
                ]);
            }
            $validation->setRule('status','Status','required|in_list[0,1]',[
                'required' => lang('Admin.error.invalidStatus'),
                'in_list' => lang('Admin.error.invalidStatus'),
            ]);
            if($validation->withRequest($this->request)->run() == false){
                $error_msg = $validation->listErrors();
            }elseif (defined('HDZDEMO')){
                $error_msg = 'This is not possible in demo version.';
            }else{
                $this->client->update([
                    'fullname' => esc($this->request->getPost('fullname')),
                    'email' => esc($this->request->getPost('email')),
                    'password' => ($this->request->getPost('password') ? password_hash($this->request->getPost('password'), PASSWORD_BCRYPT) : $user->password),
                    'status' => $this->request->getPost('status')
                ], $user->id);
                $this->session->setFlashdata('form_success', lang('Admin.users.accountUpdated'));
                return redirect()->to(current_url());
            }
        }
        if(defined('HDZDEMO')){
            $user->email = '[Hidden in demo]';
        }
        return view('staff/users_form',[
            'error_msg' => isset($error_msg) ? $error_msg : null,
            'success_msg' => $this->session->has('form_success') ? $this->session->getFlashdata('form_success') : null,
            'user' => $user
        ]);
    }
}