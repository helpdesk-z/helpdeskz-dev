<?php
/**
 * @package EvolutionScript
 * @author: EvolutionScript S.A.C.
 * @Copyright (c) 2010 - 2020, EvolutionScript.com
 * @link http://www.evolutionscript.com
 */
namespace App\Controllers;

use CodeIgniter\Database\Exceptions\DatabaseException;
use Config\Database;
use Config\Helpdesk;
use Config\Services;

define('INSTALL_ENVIRONMENT', 1);

class Install extends BaseController
{
    private $installPath = HDZ_PATH.'install'.DIRECTORY_SEPARATOR;
    private $current_version;
    private $error_msg;
    private function isLocked()
    {
        $lock_file = WRITEPATH.'cache/install.config';
        if(file_exists($lock_file)){
            $this->error_msg[] = lang('Install.isLocked');
            return true;
        }else{
            return false;
        }
    }

    private function error_page()
    {
        return view('install/install_error',[
            'error_msg' => $this->error_msg
        ]);
    }
    public function home()
    {
        return view('install/home');
    }

    public function install()
    {
        if($this->isLocked()){
            return $this->error_page();
        }
        if($this->request->getPost('do') == 'submit'){
            return $this->checkSetup();
        }
        return view('install/install');
    }

    private function checkSetup()
    {
        if($this->isLocked()){
            return $this->error_page();
        }
        if(!$this->checkPaths()){
            return $this->error_page();
        }
        if(!$this->checkDatabase()){
            return $this->error_page();
        }
        if($this->request->getPost('action') == 'install'){
            $validation = Services::validation();
            $validation->setRules([
                'fullname' => 'required|alpha_numeric_space',
                'email' => 'required|valid_email',
                'username' => 'required|alpha_dash',
                'password' => 'required|min_length[6]',
                'password_confirm' => 'matches[password]',
            ],[
                'fullname' => [
                    'required' => lang('Install.error.fullName'),
                    'alpha_numeric_space' => lang('Install.error.fullName')
                ],
                'email' => [
                    'required' => lang('Install.error.email'),
                    'valid_email' => lang('Install.error.email'),
                ],
                'username' => [
                    'required' => lang('Install.error.username'),
                    'alpha_dash' => lang('Install.error.username'),
                ],
                'password' => [
                    'required' => lang('Install.error.password'),
                    'min_length' => lang('Install.error.password'),
                ],
                'password_confirm' => [
                    'matches' => lang('Install.error.passwordConfirmation')
                ],
            ]);
            if($validation->withRequest($this->request)->run() == false){
                $error_msg = $validation->listErrors();
            }else{
                if(!$this->importDBFile('db.sql')){
                    return $this->error_page();
                }
                $db = Database::connect();
                $db->table('staff')
                    ->insert([
                        'username' => $this->request->getPost('username'),
                        'password' => password_hash($this->request->getPost('password'), PASSWORD_BCRYPT),
                        'fullname' => $this->request->getPost('fullname'),
                        'email' => $this->request->getPost('email'),
                        'registration' => time(),
                        'admin' => 1,
                        'active' => 1
                    ]);
                $host = parse_url(site_url());
                $host = $host['host'];
                $siteName = ucwords($host);
                $db->table('config')
                    ->update([
                        'site_name' => $siteName,
                        'windows_title' => $siteName
                    ], ['id' => 1]);
                $db->table('emails')
                    ->insert([
                        'default' => 1,
                        'name' => $siteName,
                        'email' => 'support@'.$host,
                        'department_id' => 1,
                        'created' => time(),
                        'outgoing_type' => 'php',
                        'smtp_host' => 'mail.gmail.com',
                        'smtp_port' => '587',
                        'smtp_encryption' => 'tls',
                        'smtp_username' => 'username@gmail.com',
                        'smtp_password' => '',
                        'incoming_type' => '',
                        'imap_host' => '',
                        'imap_port' => '',
                        'imap_username' => '',
                        'imap_password' => '',
                        'imap_minutes' => 5
                    ]);
                $this->writeLocker();
                return redirect()->route('install_complete');
            }
        }
        return view('install/install_setup',[
            'error_msg' => isset($error_msg) ? $error_msg : null
        ]);
    }

    public function installComplete()
    {
        return view('install/install_complete');
    }


    public function upgrade()
    {
        if(!$this->checkPaths()){
            return $this->error_page();
        }
        if(!$this->checkDatabase()){
            return $this->error_page();
        }
        if(!$this->canUpgrade()){
            return $this->error_page();
        }

        if($this->request->getPost('do') == 'submit')
        {
            return $this->upgradeCredentials();
        }

        return view('install/upgrade');
    }

    public function upgradeCredentials()
    {
        if(!$this->upgradeDB($this->current_version)){
            return $this->error_page();
        }
        $this->writeLocker();
        return redirect()->route('install_complete');
    }



    /*
     * ==========================================================
     * Verify writable paths and database connection
     * ==========================================================
     */
    private function checkPaths()
    {
        if ( function_exists('version_compare') && version_compare(PHP_VERSION,'7.2','<') ){
            $error_msg[] = lang_replace('Install.error.phpVersion', [
                '%req%' => '7.2',
                '%used%' => PHP_VERSION,
                '[b]' => '<span class="font-weight-bold">',
                '[/b]' => '</span>'
            ]);
        }
        if (  !function_exists('mysqli_connect') ){
            $error_msg[] = lang('Install.error.mysql');
        }
        $path_list = [
            WRITEPATH.'attachments',
            WRITEPATH.'logs',
            WRITEPATH.'mails',
            WRITEPATH.'session',
            WRITEPATH.'uploads',
            Helpdesk::UPLOAD_PATH,
            Helpdesk::UPLOAD_PATH.DIRECTORY_SEPARATOR.'thumbs'
        ];
        foreach ($path_list as $path){
            if(!is_really_writable($path)){
                $error_msg[] = lang_replace('Install.error.writable', [
                    '%folder%' => $path,
                    '[b]' => '<span class="font-weight-bold">',
                    '[/b]' => '</span>'
                ]);
            }
        }
        if(isset($error_msg)){
            $this->error_msg = $error_msg;
            return false;
        }
        return true;
    }

    private function checkDatabase()
    {
        $db = Database::connect();
        try {
            $db->connect();
        }catch (\Exception $exception){
            $db_error = $db->error();
            $error_msg[] = 'DB: ['.$db_error['code'].'] '.$db_error['message'];
            $this->error_msg = $error_msg;
            return false;
        }
        return true;
    }

    /*
     * ===========================================================
     * Upgrade verification and detection of installed version
     * ===========================================================
     */
    private function canUpgrade()
    {
        $lock_file = WRITEPATH.'cache/install.config';
        $this->current_version = null;
        if(file_exists($lock_file)){
            $content = file_get_contents($lock_file);
            $content = unserialize($content);
            $content = is_array($content) ? $content : array();
            if(isset($content['version'])){
                $this->current_version = $content['version'];
            }
        }else{
            $db = Database::connect();
            if($db->tableExists('settings')){
                $q = $db->table('settings')->where('field', 'helpdeskz_version')->get();
                if($q->resultID->num_rows > 0){
                    $this->current_version = $q->getRow()->value;
                }
            }
        }
        if(isset($this->current_version)){
            if($this->current_version >= HDZ_VERSION){
                $this->error_msg[] = lang('Install.upgradeStop');
                return false;
            }
        }else{
            $this->error_msg[] = lang('Install.upgradeNoVersion');
            return false;
        }

        return true;
    }

    /*
     * ===========================================================
     * DB Options
     * ===========================================================
     */
    private function importDBFile($file)
    {
        $dbFilePath = $this->installPath.$file;
        if(!file_exists($dbFilePath)){
            $this->error_msg = [lang_replace('Install.error.file', ['%file%' => $dbFilePath])];
            return false;
        }
        $db = Database::connect();
        $dbFile = file_get_contents($dbFilePath);
        $dbFile = str_replace('{{db_prefix}}', $db->getPrefix(), $dbFile);
        // Temporary variable, used to store current query
        $templine = '';
        $lines = explode("\n", $dbFile);
        foreach ($lines as $line)
        {
            // Skip it if it's a comment
            if (substr($line, 0, 2) == '--' || $line == '')
                continue;
            // Add this line to the current segment
            $templine .= $line;
            // If it has a semicolon at the end, it's the end of the query
            if (substr(trim($line), -1, 1) == ';')
            {
                // Perform the query
                $db->query($templine);
                // Reset temp variable to empty
                $templine = '';
            }
        }
        return true;
    }

    private function upgradeDB($old_version)
    {
        if($old_version <= '1.0.2'){
            if(!$this->importDBFile('upgrade_1.0.2.sql')){
                return false;
            }
            $db = Database::connect();
            //articles
            $articles = $db->table('articles');
            $articles->set('last_update','date', false)->update();
            $q = $articles->get();
            foreach($q->getResult() as $item){
                $agent = $db->table('staff')
                    ->select('id')
                    ->where('fullname',$item->author)
                    ->get(1);
                if($agent->resultID->num_rows > 0){
                    $articles->update(['staff_id' => $agent->getRow()->id], ['id'=>$item->id]);
                }
            }
            //canned_response
            $db->table('canned_response')->update(['date' => time()]);
            //config
            $q = $db->table('settings')->get();
            $settings = array();
            foreach ($q->getResult() as $item){
                $settings[$item->field] = $item->value;
            }
            $db->table('config')->update([
                'site_name' => $settings['site_name'],
                'windows_title' => $settings['windows_title'],
                'page_size' => $settings['page_size'],
                'date_format' => $settings['date_format'],
                'timezone' => $settings['timezone'],
                'maintenance' => $settings['maintenance'],
                'login_attempt' => $settings['login_attempt'],
                'login_attempt_minutes' => $settings['login_attempt_minutes'],
                'reply_order' => $settings['show_tickets'],
                'tickets_page' => $settings['tickets_page'],
                'tickets_replies' => $settings['tickets_replies'],
                'overdue_time' => $settings['overdue_time'],
                'ticket_autoclose' => $settings['closeticket_time'],
                'ticket_attachment' => $settings['ticket_attachment'],
                'kb_articles' => $settings['knowledgebase_articlesundercat'],
                'kb_maxchar' => $settings['knowledgebase_articlemaxchar'],
                'kb_popular' => $settings['knowledgebase_mostpopulartotal'],
                'kb_latest' => $settings['knowledgebase_newesttotal']
            ]);
            //emails
            $db->table('emails')
                ->insert([
                    'default' => 1,
                    'name' => $settings['site_name'],
                    'email' => $settings['email_ticket'],
                    'department_id' => 1,
                    'created' => time(),
                    'outgoing_type' => ($settings['smtp'] == 'no' ? 'php' : 'smtp'),
                    'smtp_host' => $settings['smtp_hostname'],
                    'smtp_port' => $settings['smtp_port'],
                    'smtp_encryption' => $settings['smtp_ssl'],
                    'smtp_username' => $settings['smtp_username'],
                    'smtp_password' => $settings['smtp_password'],
                    'incoming_type' => '',
                    'imap_host' => $settings['smtp_hostname'],
                    'imap_port' => '987',
                    'imap_username' => '',
                    'imap_password' => '',
                    'imap_minutes' => 5
                ]);
        }else{
            $this->importDBFile('upgrade_'.$this->current_version.'.sql');
        }
        return true;
    }

    private function writeLocker()
    {
        file_put_contents(WRITEPATH.'cache/install.config', serialize(['version' => HDZ_VERSION,'date'=>time()]));
    }
}