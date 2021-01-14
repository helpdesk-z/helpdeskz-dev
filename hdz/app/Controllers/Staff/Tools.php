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

class Tools extends BaseController
{
    public function customFields()
    {
        if($this->staff->getData('admin') != 1){
            return redirect()->route('staff_dashboard');
        }

        $tickets = Services::tickets();
        if($this->request->getMethod() == 'get')
        {
            if(is_numeric($this->request->getGet('field_id'))){
                if($this->request->getGet('action') == 'move_down'){

                    $tickets->customFieldMoveDown($this->request->getGet('field_id'));
                }elseif ($this->request->getGet('action') == 'move_up'){
                    $tickets->customFieldMoveUp($this->request->getGet('field_id'));
                }
                return redirect()->to(current_url());
            }
        }
        elseif ($this->request->getMethod() == 'post')
        {
            if($this->request->getPost('do') == 'remove')
            {
                $validation = Services::validation();
                $validation->setRule('field_id', 'field_id', 'required|is_natural_no_zero|is_not_unique[custom_fields.id]');
                if($validation->withRequest($this->request)->run() == false){
                    $error_msg = lang('Admin.tools.invalidCustomField');
                }elseif (defined('HDZDEMO')){
                    $error_msg = 'This is not possible in demo version.';
                }else{
                    $tickets->deleteCustomField($this->request->getPost('field_id'));
                    $this->session->setFlashdata('form_success',lang('Admin.tools.customFieldRemoved'));
                    return redirect()->to(current_url());
                }
            }
        }


        return view('staff/custom_fields',[
            'error_msg' => isset($error_msg) ? $error_msg : null,
            'success_msg' => $this->session->has('form_success') ? $this->session->getFlashdata('form_success') : null,
            'customFields' => $tickets->getCustomFields(),
            'customFieldsType' => $tickets->getCustomFieldsType(),
            'first_position' => $tickets->customFieldFirstPosition(),
            'last_position' => $tickets->customFieldLastPosition()
        ]);
    }
    public function customFieldsCreate()
    {
        if($this->staff->getData('admin') != 1){
            return redirect()->route('staff_dashboard');
        }

        $tickets = Services::tickets();
        if($this->request->getPost('do') == 'submit')
        {
            $validation = Services::validation();
            $validation->setRule('title', lang('Admin.tools.fieldTitle'),'required');
            $validation->setRule('type', lang('Admin.tools.fieldType'),'required|in_list['.implode(',', array_keys($tickets->getCustomFieldsType())).']');
            if(in_array($this->request->getPost('type'), ['checkbox','radio','select'])){
                $validation->setRule('options',lang('Admin.tools.options'),'required');
            }
            $validation->setRule('required',lang('Admin.tools.required'),'required|in_list[0,1]');
            $validation->setRule('department_list', lang('Admin.tickets.departments'),'required|in_list[0,1]');
            if($this->request->getPost('department_list') == '1'){
                $validation->setRule('departments', lang('Admin.tickets.departments'), 'required');
            }
            if($validation->withRequest($this->request)->run() == false){
                $error_msg = $validation->listErrors();
            }elseif (defined('HDZDEMO')){
                $error_msg = 'This is not possible in demo version.';
            }
            if($this->request->getPost('department_list') == '1'){
                if(!is_array($this->request->getPost('departments'))){
                    $error_msg = lang('Admin.error.selectDepartment');
                }else{
                    $deparments = Services::departments();
                    $department_list = array();
                    foreach ($this->request->getPost('departments') as $dep_id){
                        if(!$dep = $deparments->getByID($dep_id)){
                            $error_msg = lang('Admin.error.departmentNotExist');
                            continue;
                        }else{
                            $department_list[] = $dep->id;
                        }
                    }
                    if(count($department_list) == 0){
                        $error_msg = lang('Admin.error.selectDepartment');
                    }
                }
            }
            if(!isset($error_msg)){
                $tickets->insertCustomField();
                $this->session->setFlashdata('form_success', lang('Admin.tools.customFieldInserted'));
                return redirect()->to(current_url());
            }
        }
        return view('staff/custom_fields_form',[
            'error_msg' => isset($error_msg) ? $error_msg : null,
            'success_msg' => $this->session->has('form_success') ? $this->session->getFlashdata('form_success') : null,
            'customFieldsType' => $tickets->getCustomFieldsType(),
            'selectedDepartments' => $this->request->getPost('departments'),
        ]);
    }

    public function customFieldsEdit($field_id)
    {
        if($this->staff->getData('admin') != 1){
            return redirect()->route('staff_dashboard');
        }

        $tickets = Services::tickets();
        if(!$data = $tickets->getCustomField($field_id))
        {
            return redirect()->route('staff_custom_fields');
        }
        if($this->request->getPost('do') == 'submit')
        {
            $validation = Services::validation();
            $validation->setRule('title', lang('Admin.tools.fieldTitle'),'required');
            $validation->setRule('type', lang('Admin.tools.fieldType'),'required|in_list['.implode(',', array_keys($tickets->getCustomFieldsType())).']');
            if(in_array($this->request->getPost('type'), ['checkbox','radio','select'])){
                $validation->setRule('options',lang('Admin.tools.options'),'required');
            }
            $validation->setRule('required',lang('Admin.tools.required'),'required|in_list[0,1]');
            $validation->setRule('department_list', lang('Admin.tickets.departments'),'required|in_list[0,1]');
            if($this->request->getPost('department_list') == '1'){
                $validation->setRule('departments', lang('Admin.tickets.departments'), 'required');
            }
            if($validation->withRequest($this->request)->run() == false){
                $error_msg = $validation->listErrors();
            }elseif (defined('HDZDEMO')){
                $error_msg = 'This is not possible in demo version.';
            }
            if($this->request->getPost('department_list') == '1'){
                if(!is_array($this->request->getPost('departments'))){
                    $error_msg = lang('Admin.error.selectDepartment');
                }else{
                    $deparments = Services::departments();
                    $department_list = array();
                    foreach ($this->request->getPost('departments') as $dep_id){
                        if(!$dep = $deparments->getByID($dep_id)){
                            $error_msg = lang('Admin.error.departmentNotExist');
                            continue;
                        }else{
                            $department_list[] = $dep->id;
                        }
                    }

                    if(count($department_list) == 0){
                        $error_msg = lang('Admin.error.selectDepartment');
                    }
                }
            }
            if(!isset($error_msg)){
                $tickets->updateCustomField($data->id);
                $this->session->setFlashdata('form_success', lang('Admin.tools.customFieldUpdated'));
                return redirect()->to(current_url());
            }
        }

        if(in_array($data->type, ['checkbox','radio','select'])){
            $data->options = $data->value;
            $data->value = '';
        }elseif (in_array($data->type, ['text','textarea','password'])){
            $data->options = '';
        }else{
            $data->value ='';
            $data->options ='';
        }
        return view('staff/custom_fields_form',[
            'error_msg' => isset($error_msg) ? $error_msg : null,
            'success_msg' => $this->session->has('form_success') ? $this->session->getFlashdata('form_success') : null,
            'customFieldsType' => $tickets->getCustomFieldsType(),
            'selectedDepartments' => $this->request->getPost('departments'),
            'customField' => $data
        ]);
    }
}