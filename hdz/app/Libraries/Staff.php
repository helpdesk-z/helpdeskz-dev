<?php
/**
 * @package EvolutionScript
 * @author: EvolutionScript S.A.C.
 * @Copyright (c) 2010 - 2020, EvolutionScript.com
 * @link http://www.evolutionscript.com
 */

namespace App\Libraries;


use Config\Database;
use Config\Services;

class Staff
{
    private $staffModel;
    private $is_online=null;
    private $staff_departments;
    public function __construct()
    {
        $this->staffModel = new \App\Models\Staff();
    }
    public function isOnline()
    {
        if(is_null($this->is_online)){
            $this->is_online = false;
            $session = Services::session();
            if(is_numeric(get_cookie('sid')) && get_cookie('shash') != ''){
                $this->is_online = $this->validate_session(get_cookie('sid'), get_cookie('shash'));
            }elseif($session->has('sid') && $session->has('shash')){
                $this->is_online = $this->validate_session($session->get('sid'), $session->get('shash'));
            }
        }
        return $this->is_online;
    }

    private function validate_session($user_id, $hash)
    {
        if(!$data = $this->getRow(['id' => $user_id])){
            return $this->logout();
        }
        $request = Services::request();
        if(defined('HDZDEMO')){
            if(!password_verify(sha1($data->password.'ThisIsDemoToken'), $hash)){
                return $this->logout();
            }
        }else{
            if(!password_verify(sha1($data->password.$data->token).':'.$request->getUserAgent(), $hash)){
                return $this->logout();
            }
        }

        $this->user_data = $data;
        $this->user_data->department = unserialize($this->user_data->department);
        $this->user_data->department = (is_array($this->user_data->department)?$this->user_data->department:array());
        return true;
    }

    public function create_session($id, $password, $token, $remember=true)
    {
        $request = Services::request();
        $session = Services::session();
        if(defined('HDZDEMO')){
            $hash = sha1($password.'ThisIsDemoToken');
        }else{
            $hash = sha1($password.$token).':'.$request->getUserAgent();
        }
        $hash = password_hash($hash, PASSWORD_BCRYPT);
        $session->set('sid', $id);
        $session->set('shash', $hash);
        if($remember){
            set_cookie('sid', $id, 60*60*24*365);
            set_cookie('shash', $hash, 60*60*24*365);
        }
    }

    public function logout()
    {
        set_cookie('sid','');
        set_cookie('shash','');
        Services::session()->destroy();
        return redirect()->withCookies()->route('staff_login');
    }

    public function updatePassword($password)
    {
        $hash = password_hash($password, PASSWORD_BCRYPT);
        $this->update([
            'password' => $hash
        ]);
        $this->create_session($this->getData('id'), $hash, $this->getData('token'), true);
    }

    /*
     * ------------------------------
     * Login
     * ------------------------------
     */
    public function verifyPassword($staffData)
    {
        $password = Services::request()->getPost('password');
        if(!password_verify($password, $staffData->password)){
            //Verify if it belongs to old version (1.2 or less)
            if(sha1($password) != $staffData->password){
                return false;
            }else{
                //Update password
                $this->update([
                    'password' => password_hash($password, PASSWORD_BCRYPT)
                ], $staffData->id);
            }
        }
        return true;
    }
    public function login($staff_data, $remember=true)
    {
        $this->update([
            'login' => time(),
            'last_login' => ($staff_data->login == 0 ? time() : $staff_data->login)
        ], $staff_data->id);
        $this->addLoginLog($staff_data->id, true);
        $token = random_string('sha1');
        $this->update(['token' => $token],$staff_data->id);
        $this->create_session($staff_data->id, $staff_data->password, $token, $remember);
    }

    public function isLocked($ip_address=null)
    {
        //Delete logs
        $settings = Services::settings();
        $request = Services::request();
        $db = Database::connect();
        $ip_address = is_null($ip_address) ? $request->getIPAddress() : $ip_address;
        $builder = $db->table('login_attempt');
        $builder->delete([
                'date<' => time()-(60*$settings->config('login_attempt_minutes'))
            ]);
        //Verify
        $q = $builder->select('attempts, date')
            ->where('ip', $ip_address)
            ->get(1);
        if($q->resultID->num_rows == 0){
            return false;
        }
        $result = $q->getRow();
        if($settings->config('login_attempt') > 0 && $result->attempts >= $settings->config('login_attempt')){
            return true;
        }
        return false;
    }

    public function addLoginAttempt($ip_address=null)
    {
        $settings = Services::settings();
        if($settings->config('login_attempt') == 0){
            return '0';
        }
        $request = Services::request();
        $db = Database::connect();
        $builder = $db->table('login_attempt');
        $ip_address = (!is_null($ip_address) ? $ip_address : $request->getIPAddress());
        $q = $builder->where('ip', $ip_address)
            ->get(1);
        if($q->resultID->num_rows == 0){
            $builder->insert([
                'ip' => $ip_address,
                'attempts' => 1,
                'date' => time()
            ]);
            return '1';
        }
        $result = $q->getRow();
        $builder->set('attempts','attempts+1', false)
            ->set('date', time())
            ->where('ip', $result->ip)
            ->update();
        return ($result->attempts+1);
    }

    public function addLoginLog($staff_id, $success=false, $ip_address=null)
    {
        $request = Services::request();
        $db = Database::connect();
        $user_agent = (!is_null($ip_address) ? 'HelpDeskZ API' : $request->getUserAgent());
        $ip_address = (!is_null($ip_address) ? $ip_address : $request->getIPAddress());
        if($success){
            $builder = $db->table('login_attempt');
            $builder->where('ip', $ip_address)
                ->delete();
        }

        $builder = $db->table('login_log');
        $builder->insert([
            'date' => time(),
            'staff_id' => $staff_id,
            'ip' => $ip_address,
            'agent' => $user_agent,
            'success' => $success
        ]);
    }

    /*
     * -----------------------------------
     * Departments
     * -----------------------------------
     */
    public function countTicketsByStatus($status)
    {

        $where = array();
        foreach ($this->getDepartments() as $item){
            $where[] = array('department_id' => $item->id);
        }
        $ticketsModel = new \App\Models\Tickets();
        if(count($where) > 0){
            $ticketsModel->groupStart();
            foreach ($where as $item){
                $ticketsModel->orWhere($item);
            }
            $ticketsModel->groupEnd();
        }
        return $ticketsModel->where('status', $status)
            ->countAllResults();
    }

    public function countTicketsFromDepartments()
    {
        $where = array();
        foreach ($this->getDepartments() as $item){
            $where[] = array('d.id' => $item->id);
        }
        $where[] = array('d.id' => 5);

        if(count($where) > 0){
            foreach ($where as $item)
            {
                $this->db->or_where($item);
            }

        }
        $q = $this->db->select("d.id, d.name, 
        (SELECT COUNT(*) FROM ".$this->db->dbprefix('tickets')." WHERE department_id=d.id AND status=1) as open, 
        (SELECT COUNT(*) FROM ".$this->db->dbprefix('tickets')." WHERE department_id=d.id AND status=2) as answered, 
        (SELECT COUNT(*) FROM ".$this->db->dbprefix('tickets')." WHERE department_id=d.id AND status=3) as awaiting_reply,
        (SELECT COUNT(*) FROM ".$this->db->dbprefix('tickets')." WHERE department_id=d.id AND status=4) as in_progress,
        (SELECT COUNT(*) FROM ".$this->db->dbprefix('tickets')." WHERE department_id=d.id AND status=5) as closed", false)
            ->order_by('d.dep_order','asc')
            ->from('departments as d')
            ->get();
        if($q->num_rows() == 0){
            return null;
        }
        $r = $q->result();
        $q->free_result();
        return $r;
    }

    /*
     * -------------------------------------
     * Agents
     * -------------------------------------
     */
    public function getAgents()
    {
        $q = $this->staffModel->orderBy('id','asc')
            ->get();
        $r = $q->getResult();
        $q->freeResult();;
        return $r;
    }

    public function newAgent($fullname, $username, $email, $password, $admin=0, $departments='', $active=1)
    {
        $this->staffModel->protect(false);
        $departments = is_array($departments) ? $departments : array();
        $this->staffModel->insert([
            'fullname' => esc($fullname),
            'username' => $username,
            'email' => $email,
            'password' => password_hash($password, PASSWORD_BCRYPT),
            'registration' => time(),
            'admin' => $admin,
            'department' => serialize($departments),
            'active' => $active
        ]);
        $this->staffModel->protect(true);
        return $this->staffModel->getInsertID();
    }

    public function updateAgent($id, $fullname, $username, $email, $password, $admin=0, $departments='', $active=1)
    {
        $this->staffModel->protect(false);
        $departments = is_array($departments) ? $departments : array();
        if($password != ''){
            $this->staffModel->set('password', password_hash($password, PASSWORD_BCRYPT));
        }
        $this->staffModel->set([
            'fullname' => esc($fullname),
            'username' => $username,
            'email' => $email,
            'admin' => $admin,
            'department' => serialize($departments),
            'active' => $active
        ])->update($id);
        $this->staffModel->protect(false);
        return true;
    }

    public function removeAgent($id)
    {
        $this->staffModel->delete($id);
        $db = Database::connect();
        $db->table('login_log')
            ->where('staff_id', $id)
            ->delete();
    }
    /*
     * ---------------------------------
     * Database queries
     * ---------------------------------
     */
    public function getRow($data=array(),$select='*')
    {
        $q = $this->staffModel->select($select)
            ->where($data)
            ->get(1);
        if($q->resultID->num_rows == 0){
            return null;
        }
        return $q->getRow();
    }

    public function update($data=array(), $id=''){
        if(!is_numeric($id)){
            $id = $this->getData('id');
        }
        $this->staffModel->protect(false);
        $this->staffModel->update($id, $data);
        $this->staffModel->protect(true);
    }




    /*
     * ------------------------
     * Profile
     * ------------------------
     */
    public function getDepartments()
    {
        if(!$this->staff_departments){
            if($this->getData('admin')){
                $this->staff_departments = Services::departments()->getAll();
            }else{
                $departmentModel = new \App\Models\Departments();
                $departmentModel->groupStart();
                foreach ($this->getData('department') as $department_id){
                    $departmentModel->orWhere('id', $department_id);
                }
                $q = $departmentModel->groupEnd()
                    ->orderBy('dep_order','asc')
                    ->get();
                if($q->resultID->num_rows == 0){
                    $this->staff_departments = null;
                }else{
                    $this->staff_departments = $q->getResult();
                    $q->freeResult();
                }
            }
        }
        return $this->staff_departments;

    }

    public function getData($var)
    {
        return isset($this->user_data->$var) ? $this->user_data->$var : null;
    }

    public function lastLoginLogs()
    {
        $q = $this->db->where('staff_id', $this->getData('id'))
            ->order_by('date','desc')
            ->limit(10)
            ->get('login_log');
        if($q->num_rows() == 0){
            return null;
        }
        $r = $q->result();
        $q->free_result();
        return $r;
    }
}