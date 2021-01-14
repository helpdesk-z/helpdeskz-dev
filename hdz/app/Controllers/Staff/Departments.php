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

class Departments extends BaseController
{
    public function manage()
    {
        if($this->staff->getData('admin') != 1){
            return redirect()->route('staff_dashboard');
        }
        $departments = Services::departments();
        if($this->request->getMethod() == 'get'){
            if(is_numeric($this->request->getGet('department_id'))){
                if($this->request->getGet('action') == 'move_down'){
                    $departments->move_down($this->request->getGet('department_id'));
                }elseif ($this->request->getGet('action') == 'move_up'){
                    $departments->move_up($this->request->getGet('department_id'));
                }
                return redirect()->to(current_url());
            }
        }elseif ($this->request->getMethod() == 'post'){
            if (defined('HDZDEMO')) {
                $error_msg = 'This is not possible in demo version.';
            }elseif($this->request->getPost('do') == 'remove'){
                $departments->remove($this->request->getPost('department_id'));
                $this->session->setFlashdata('form_success',lang('Admin.tickets.departmentRemoved'));
                return redirect()->to(current_url());
            }
        }
        return view('staff/departments',[
            'error_msg' => isset($error_msg) ? $error_msg : null,
            'success_msg' => $this->session->has('form_success') ? $this->session->getFlashdata('form_success') : null,
            'first_position' => $departments->getFirstPosition(),
            'last_position' => $departments->getLastPosition(),
            'list_departments' => $departments->getAll()
        ]);
    }

    public function edit($department_id)
    {
        if($this->staff->getData('admin') != 1){
            return redirect()->route('staff_dashboard');
        }

        $departments = Services::departments();
        if(!$department = $departments->getByID($department_id)){
            return redirect()->route('staff_departments');
        }
        if($this->request->getPost('do') == 'submit'){
            $validation = Services::validation();
            $validation->setRules([
                'name' => 'required',
                'private' => 'required|in_list[0,1]'
            ],[
                'name' => [
                    'required' => lang('Admin.error.enterDepartmentName')
                ],
                'private' => [
                    'required' => lang('Admin.error.selectDepartmentType'),
                    'in_list' => lang('Admin.error.selectDepartmentType')
                ],
            ]);

            if($validation->withRequest($this->request)->run() == false){
                $error_msg = $validation->listErrors();
            }elseif (defined('HDZDEMO')){
                $error_msg = 'This is not possible in demo version.';
            }else{
                $departmentModel = new \App\Models\Departments();
                if($this->request->getPost('position') == 'start'){
                    $firstPosition = $departments->getFirstPosition();
                    $departmentModel->increment('dep_order', 1);
                    $position = $firstPosition->dep_order;
                }elseif ($this->request->getPost('position') == 'last'){
                    $lastPosition = $departments->getLastPosition();
                    $position = $lastPosition->dep_order+1;
                }elseif (is_numeric($this->request->getPost('position'))){
                    if($dep = $departments->getByID($this->request->getPost('position'))){
                        $position = $dep->dep_order+1;
                        $departmentModel->where('dep_order>', $dep->dep_order)
                            ->increment('dep_order', 1);
                    }else{
                        $position = $department->dep_order;
                    }
                }else{
                    $position = $department->dep_order;
                }
                $departments->update(
                    $department->id,
                    $this->request->getPost('name'),
                    $this->request->getPost('private'),
                    $position
                );
                $this->session->setFlashdata('form_success',lang('Admin.tickets.departmentUpdated'));
                return redirect()->to(current_url());
            }
        }
        return view('staff/departments_form',[
            'error_msg' => isset($error_msg) ? $error_msg : null,
            'success_msg' => $this->session->has('form_success') ? $this->session->getFlashdata('form_success') : null,
            'department' => $department,
            'list_departments' => $departments->getAll()
        ]);
    }

    public function create()
    {
        if($this->staff->getData('admin') != 1){
            return redirect()->route('staff_dashboard');
        }

        $departments = Services::departments();
        if($this->request->getPost('do') == 'submit'){
            $validation = Services::validation();
            $validation->setRules([
                'name' => 'required',
                'private' => 'required|in_list[0,1]'
            ],[
                'name' => [
                    'required' => lang('Admin.error.enterDepartmentName')
                ],
                'private' => [
                    'required' => lang('Admin.error.selectDepartmentType'),
                    'in_list' => lang('Admin.error.selectDepartmentType')
                ],
            ]);
            if($validation->withRequest($this->request)->run() == false){
                $error_msg = $validation->listErrors();
            }elseif (defined('HDZDEMO')){
                $error_msg = 'This is not possible in demo version.';
            }else{
                $departments->create($this->request->getPost('name'), $this->request->getPost('private'), $this->request->getPost('position'));
                $this->session->setFlashdata('form_success',lang('Admin.tickets.departmentCreated'));
                return redirect()->to(current_url());
            }
        }
        return view('staff/departments_form',[
            'error_msg' => isset($error_msg) ? $error_msg : null,
            'success_msg' => $this->session->has('form_success') ? $this->session->getFlashdata('form_success') : null,
            'list_departments' => $departments->getAll()
        ]);
    }

}