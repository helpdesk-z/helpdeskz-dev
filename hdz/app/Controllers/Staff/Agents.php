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

class Agents extends BaseController
{
    public function manage()
    {
        if($this->staff->getData('admin') != 1){
            return redirect()->route('staff_dashboard');
        }

        if($this->request->getPost('do') == 'remove'){
            if (defined('HDZDEMO')) {
                $error_msg = 'This is not possible in demo version.';
            }else{
                $this->staff->removeAgent($this->request->getPost('agent_id'));
                $this->session->setFlashdata('form_success',lang('Admin.agents.agentRemoved'));
                return redirect()->to(current_url());
            }
        }
        return view('staff/agents',[
            'error_msg' => isset($error_msg) ? $error_msg : null,
            'success_msg' => $this->session->has('form_success') ? $this->session->getFlashdata('form_success') : null,
            'agents_list' => $this->staff->getAgents()
        ]);
    }

    public function edit($agent_id)
    {
        if($this->staff->getData('admin') != 1){
            return redirect()->route('staff_dashboard');
        }

        if($agent_id == $this->staff->getData('id') || !$agent = $this->staff->getRow(['id' => $agent_id])){
            return redirect()->route('staff_agents');
        }
        if($this->request->getPost('do') == 'submit'){
            $validation = Services::validation();
            $validation->setRule('fullname', lang('Admin.form.fullName'),'required',[
                'required' => lang('Admin.error.enterFullName')
            ]);

            if($this->request->getPost('username') != $agent->username){
                $validation->setRule('username',lang('Admin.form.username'),'required|alpha_dash|is_unique[staff.username]',[
                    'required' => lang('Admin.error.enterUsername'),
                    'alpha_dash' => lang('Admin.error.enterUsername'),
                    'is_unique' => lang('Admin.error.usernameTaken')
                ]);
            }
            if($this->request->getPost('email') != $agent->email){
                $validation->setRule('email','Email','required|valid_email|is_unique[staff.email]',[
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

            $validation->setRule('admin','admin','required|in_list[0,1]',[
                'required' => lang('Admin.error.selectTypeAccess'),
                'in_list' => lang('Admin.error.selectTypeAccess')
            ]);
            $validation->setRule('active','active','required|in_list[0,1]',[
                'required' => lang('Admin.error.invalidStatus'),
                'in_list' => lang('Admin.error.invalidStatus')
            ]);

            if($validation->withRequest($this->request)->run() == false){
                $error_msg = $validation->listErrors();
            }elseif (defined('HDZDEMO')){
                $error_msg = 'This is not possible in demo version.';
            }else{
                $this->staff->updateAgent($agent->id, $this->request->getPost('fullname'),
                    $this->request->getPost('username'),
                    $this->request->getPost('email'), $this->request->getPost('password'),
                    $this->request->getPost('admin'), $this->request->getPost('department'),
                    $this->request->getPost('active'));
                $this->session->setFlashdata('form_success',lang('Admin.agents.informationUpdated'));
                return redirect()->to(current_url());
            }
        }
        return view('staff/agents_form',[
            'error_msg' => isset($error_msg) ? $error_msg : null,
            'success_msg' => $this->session->has('form_success') ? $this->session->getFlashdata('form_success') : null,
            'agent' => $agent,
            'departments' => Services::departments()->getAll()
        ]);
    }

    public function create()
    {
        if($this->staff->getData('admin') != 1){
            return redirect()->route('staff_dashboard');
        }

        if($this->request->getPost('do') == 'submit'){
            $validation = Services::validation();
            $validation->setRules([
                'fullname' => 'required',
                'username' => 'required|alpha_dash|is_unique[staff.username]',
                'email' => 'required|valid_email|is_unique[staff.email]',
                'password' => 'required|min_length[6]',
                'admin' => 'required|in_list[0,1]',
                'active' => 'required|in_list[0,1]',
            ],[
                'fullname' => [
                    'required' => lang('Admin.error.enterFullName')
                ],
                'username' => [
                    'required' => lang('Admin.error.enterUsername'),
                    'alpha_dash' => lang('Admin.error.enterUsername'),
                    'is_unique' => lang('Admin.error.usernameTaken')
                ],
                'email' => [
                    'required' => lang('Admin.error.enterValidEmail'),
                    'valid_email' => lang('Admin.error.enterValidEmail'),
                    'is_unique' => lang('Admin.error.emailTaken'),
                ],
                'password' => [
                    'required' => lang('Admin.error.enterPassword'),
                    'min_length' => lang('Admin.error.enterPassword')
                ],
                'admin' => [
                    'required' => lang('Admin.error.selectTypeAccess'),
                    'in_list' => lang('Admin.error.selectTypeAccess')
                ],
                'active' => [
                    'required' => lang('Admin.error.invalidStatus'),
                    'in_list' => lang('Admin.error.invalidStatus')
                ]
            ]);


            if($validation->withRequest($this->request)->run() == false){
                $error_msg = $validation->listErrors();
            }elseif (defined('HDZDEMO')){
                $error_msg = 'This is not possible in demo version.';
            }else{
                $this->staff->newAgent($this->request->getPost('fullname'), $this->request->getPost('username'),
                    $this->request->getPost('email'), $this->request->getPost('password'),
                    $this->request->getPost('admin'), $this->request->getPost('department'),
                    $this->request->getPost('active'));
                $this->session->setFlashdata('form_success',lang('Admin.agents.agentCreated'));
                return redirect()->to(current_url());
            }
        }
        return view('staff/agents_form',[
            'error_msg' => isset($error_msg) ? $error_msg : null,
            'success_msg' => $this->session->has('form_success') ? $this->session->getFlashdata('form_success') : null,
            'departments' => Services::departments()->getAll()
        ]);
    }
}