<?php
/**
 * @package EvolutionScript
 * @author: EvolutionScript S.A.C.
 * @Copyright (c) 2010 - 2020, EvolutionScript.com
 * @link http://www.evolutionscript.com
 */

namespace App\Libraries;


use App\Models\ApiModel;
use Config\Services;

class Api
{
    private $error_msg = '';
    private $model;
    private $api_info;
    public function __construct()
    {
        $this->model = new ApiModel();
    }
    public function authentication()
    {
        $request = Services::request();
        $token = $request->getServer('HTTP_TOKEN') ?: null;
        if(!isset($token)){
            $this->error_msg = lang('Api.error.authentication');
            return false;
        }
        if(!$api_info = $this->getRow(['token' => $token])){
            $this->error_msg = lang('Api.error.authentication');
            return false;
        }
        if($api_info->active != 1){
            $this->error_msg = lang('Api.error.authentication');
            return false;
        }
        if(!$this->validateIP($request->getIPAddress(), $api_info->ip_address)){
            $this->error_msg = lang_replace('Api.error.ipAllowed', ['%ip%' => $request->getIPAddress()]);
            return false;
        }
        $this->api_info = $api_info;
        return true;
    }

    private function validateIP($ip_request, $list_ip)
    {
        if($list_ip == ''){
            return true;
        }
        $ip_addresses = explode(', ', $list_ip);
        if(in_array($ip_request, $ip_addresses)){
            return true;
        }
        if(filter_var($ip_request, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)){
            $ip = explode('.', $ip_request);
            $ip_checker = $ip[0].'.'.$ip[1].'.'.$ip[2].'.*';
            if(in_array($ip_checker, $ip_addresses)){
                return true;
            }
            $ip_checker = $ip[0].'.'.$ip[1].'.*.*';
            if(in_array($ip_checker, $ip_addresses)){
                return true;
            }
            $ip_checker = $ip[0].'.*.*.*';
            if(in_array($ip_checker, $ip_addresses)){
                return true;
            }
        }
        return false;
    }

    public function validatePermission($permission)
    {
        if(!isset($this->api_info)){
            $this->error_msg = lang('Api.error.authentications');
            return false;
        }
        $permission = explode('/', $permission);
        $lang = 'Api.permissionsList.'.$permission[0].'_'.$permission[1];
        $permissionList = unserialize($this->api_info->permissions);
        $permissionList = is_array($permissionList) ? $permissionList : array();
        if(!isset($permissionList[$permission[0]][$permission[1]])){
            $this->error_msg = lang('Api.error.noAllowed').' '.lang($lang);
            return false;
        }
        return true;
    }

    public function showError($msg=null)
    {
        if(!is_null($msg)){
            $this->error_msg = $msg;
        }
        return $this->output($this->error_msg, true, 400);
    }

    public function output($output, $error=false, $code=200)
    {
        if($error){
            $body['error'] = 1;
        }else{
            $body['success'] = 1;
        }
        if(is_array($output)){
            $body = array_merge($body, $output);
        }else{
            $body['message'] = $output;
        }

        return Services::response()->setStatusCode($code)
            ->setJSON($body);
    }


    /*
     * Database Actions
     */

    public function getList()
    {
        return $this->model->orderBy('date','desc')
            ->findAll();
    }

    public function permissionList()
    {
        $permissions = [
            'users' => [
                'name' => lang('Api.permissionsList.users'),
                'options' => [
                    'create' => lang('Api.permissionsList.users_create'),
                    'read' => lang('Api.permissionsList.users_read'),
                    'update' => lang('Api.permissionsList.users_update'),
                    'delete' => lang('Api.permissionsList.users_delete')
                ]
            ],
            'departments' => [
                'name' => lang('Api.permissionsList.departments'),
                'options' => [
                    'create' => lang('Api.permissionsList.departments_create'),
                    'read' => lang('Api.permissionsList.departments_read'),
                    'update' => lang('Api.permissionsList.departments_update'),
                    'delete' => lang('Api.permissionsList.departments_delete')
                ]
            ],
            'tickets' => [
                'name' => lang('Api.permissionsList.tickets'),
                'options' => [
                    'create' => lang('Api.permissionsList.tickets_create'),
                    'read' => lang('Api.permissionsList.tickets_read'),
                    'update' => lang('Api.permissionsList.tickets_update'),
                    'delete' => lang('Api.permissionsList.tickets_delete'),
                ]
            ],
            'messages' => [
                'name' => lang('Api.permissionsList.messages'),
                'options' => [
                    'create' => lang('Api.permissionsList.messages_create'),
                    'read' => lang('Api.permissionsList.messages_read')
                ]
            ],
            'attachments' => [
                'name' => lang('Api.permissionsList.attachments'),
                'options' => [
                    'read' => lang('Api.permissionsList.attachments_read'),
                    'download' => lang('Api.permissionsList.attachments_download'),
                    'delete' => lang('Api.permissionsList.attachments_delete')
                ]
            ],
            'staff' => [
                'name' => lang('Api.permissionsList.staff'),
                'options' => [
                    'read' => lang('Api.permissionsList.staff_read'),
                    'auth' => lang('Api.permissionsList.staff_auth')
                ]
            ]
        ];
        return $permissions;
    }

    public function create($apiName, $permissions, $active=1)
    {
        $permissions = is_array($permissions) ? $permissions : array();
        $this->model->protect(false);
        $ip_address = Services::request()->getPost('ip_address');
        $exp = explode(',', $ip_address);
        $ip_address = array_map(function ($e){ return trim($e);}, $exp);
        $ip_address = implode(',', $ip_address);
        return $this->model->insert([
            'name' => esc($apiName),
            'token' => random_string('alnum', 60),
            'date' => time(),
            'ip_address' => $ip_address,
            'last_update' => time(),
            'permissions' => serialize($permissions),
            'active' => $active
        ]);
    }

    public function getRow($where)
    {
        $q = $this->model->where($where)
            ->get(1);
        if($q->resultID->num_rows == 0){
            return null;
        }
        return $q->getRow();
    }

    public function update($data, $id)
    {
        $request = Services::request();
        if($request->getPost('new_token') == '1'){
            $data['token'] = random_string('alnum', 60);
        }
        $ip_address = $this->parseIps($request->getPost('ip_address'));
        $data['ip_address'] = $ip_address;
        $data['last_update'] = time();
        $this->model->protect(false)
            ->update($id, $data);
    }

    public function parseIps($ip_address)
    {
        $exp = explode(',', $ip_address);
        $ip_address = array_map(function ($e){ return trim($e);}, $exp);
        $ip_address = array_filter($ip_address, function ($e){ return isset($e) ? $e : null;});
        $ip_address = implode(', ', $ip_address);
        return $ip_address;
    }

    public function delete($id)
    {
        $this->model->protect(false)->delete($id);
    }
}