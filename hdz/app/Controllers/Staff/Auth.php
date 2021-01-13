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

class Auth extends BaseController
{
    public function login()
    {
        if($this->request->getPost('do') == 'login'){
            $validation = Services::validation();
            $validation->setRules([
                'username' => 'required|alpha_dash',
                'password' => 'required'
            ]);
            if($validation->withRequest($this->request)->run() == FALSE) {
                $error_msg = lang('Admin.login.invalidUsernamePassword');
            }elseif($this->staff->isLocked()) {
                $error_msg = lang_replace('Admin.login.lockedMinutes', [
                        '%minutes%' => $this->settings->config('login_attempt_minutes')
                    ]) .
                    '<br>' . lang_replace('Admin.login.attemptNumber', [
                        '%attempt1%' => $this->settings->config('login_attempt'),
                        '%attempt2%' => $this->settings->config('login_attempt')
                    ]);
            }elseif (!$data = $this->staff->getRow(['username' => $this->request->getPost('username')])) {
                $attempts = $this->staff->addLoginAttempt();
                $error_msg = lang('Admin.login.invalidUsernamePassword');
                if ($attempts > 0) {
                    $error_msg .= '<br>' . lang_replace('Admin.login.attemptNumber', [
                            '%attempt1%' => $attempts,
                            '%attempt2%' => $this->settings->config('login_attempt')
                        ]);
                }
            }elseif (!password_verify($this->request->getPost('password'), $data->password)){
                $this->staff->addLoginLog($data->id, false);
                $attempts = $this->staff->addLoginAttempt();
                $error_msg = lang('Admin.login.invalidUsernamePassword');
                if ($attempts > 0) {
                    $error_msg .= '<br>' . lang_replace('Admin.login.attemptNumber', [
                            '%attempt1%' => $attempts,
                            '%attempt2%' => $this->settings->config('login_attempt')
                        ]);
                }
            }elseif(!$data->active){
                $error_msg = lang('Admin.login.accountLocked');
            }else{
                $this->staff->login($data, ($this->request->getPost('remember') ? true : false));
                return redirect()->route('staff_dashboard')->withCookies();
            }
        }
        return view('staff/login',[
            'error_msg' => isset($error_msg) ? $error_msg : null
        ]);
    }

    public function logout()
    {
        return $this->staff->logout();
    }

    public function profile()
    {
        if($this->request->getPost('do') == 'delete_avatar')
        {
            $this->staff->update(['avatar' => ''], $this->staff->getData('id'));
            if($this->staff->getData('avatar') != ''){
                $avatarFile = FCPATH.'upload'.DIRECTORY_SEPARATOR.$this->staff->getData('avatar');
                if(file_exists($avatarFile)){
                    @unlink($avatarFile);
                }
            }
            $this->session->setFlashdata('form_success', lang('Admin.account.avatarRemoved'));
            return redirect()->to(current_url());
        }
        elseif($this->request->getPost('do') == 'update_password')
        {
            $validation = Services::validation();
            $validation->setRules([
                'current_password' => 'required|min_length[6]',
                'new_password' => 'required|min_length[6]',
                'new_password2' => 'matches[new_password]'
            ],[
                'current_password' => [
                    'required' => lang('Admin.error.wrongExistingPassword'),
                    'min_length' => lang('Admin.error.wrongExistingPassword')
                ],
                'new_password' => [
                    'required' => lang('Admin.form.enterNewPassword'),
                    'min_length' => lang('Admin.error.passwordTooShort')
                ],
                'new_password2' => [
                    'matches' => lang('Admin.error.passwordsNotMatches')
                ]
            ]);

            if($validation->withRequest($this->request)->run() == false){
                $error_msg = $validation->listErrors();
            }elseif(!password_verify($this->request->getPost('current_password'), $this->staff->getData('password'))){
                $error_msg = lang('Admin.error.wrongExistingPassword');
            }else{
                $this->staff->updatePassword($this->request->getPost('new_password'));
                $this->session->setFlashdata('form_success', lang('Admin.account.passwordUpdated'));
                return redirect()->withCookies()->to(current_url());
            }
        }
        elseif ($this->request->getPost('do') == 'update_profile'){
            $validation = Services::validation();
            $validation->setRule('avatar', lang('Admin.form.avatar'), 'ext_in[avatar,gif,png,jpg,jpeg],is_image[avatar]|max_size[avatar,'.max_file_size().']');
            $validation->setRule('fullname','fullname','required',[
                'required' => lang('Admin.error.enterFullName')
            ]);
            if($this->request->getPost('email') != $this->staff->getData('email')){
                $validation->setRule('fullname','email','required|valid_email|is_unique[staff.email]',[
                    'required' => lang('Admin.error.enterValidEmail'),
                    'valid_email' => lang('Admin.error.enterValidEmail'),
                    'is_unique' => lang('Admin.error.emailTaken')
                ]);
            }

            if($validation->withRequest($this->request)->run() == false){
                $error_msg = $validation->listErrors();
            }else{
                $staff_data = array();
                if($avatar = $this->request->getFile('avatar')){
                    if($avatar->isValid() && !$avatar->hasMoved()){
                        $newName = $avatar->getRandomName();
                        $imgPath = FCPATH.'upload';
                        $avatar->move($imgPath, $newName);
                        $staff_data['avatar'] = $newName;
                        if($this->staff->getData('avatar') != ''){
                            if(file_exists($imgPath.DIRECTORY_SEPARATOR.$this->staff->getData('avatar'))){
                                @unlink($imgPath.DIRECTORY_SEPARATOR.$this->staff->getData('avatar'));
                            }
                        }
                        $image = Services::image()->withFile($imgPath.DIRECTORY_SEPARATOR.$newName);
                        $width = $image->getWidth();
                        $height = $image->getHeight();
                        if($width > 300 || $height > 300){
                            $image->fit(100, 100, 'center')
                                ->save();
                        }
                    }
                }
                $this->staff->update(array_merge($staff_data, [
                    'fullname' => esc($this->request->getPost('fullname')),
                    'email' => $this->request->getPost('email'),
                    'signature' => $this->request->getPost('signature'),
                    'timezone' => (in_array($this->request->getPost('timezone'), timezone_identifiers_list())?$this->request->getPost('timezone'):$this->staff->getData('timezone')),
                ]));
                $this->session->setFlashdata('form_success', lang('Admin.account.profileUpdated'));
                return redirect()->to(current_url());
            }
        }

        return view('staff/profile',[
            'error_msg' => isset($error_msg) ? $error_msg : null,
            'success_msg' => $this->session->has('form_success') ? $this->session->getFlashdata('form_success') : null
        ]);
    }
}