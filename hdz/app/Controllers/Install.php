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
    private $installPath;
    private function checkInstallPath()
    {
        $this->installPath = HDZ_PATH.'install'.DIRECTORY_SEPARATOR;
        if(!is_dir($this->installPath)){
            return redirect()->route('home');
        }
    }
    public function home()
    {
        $this->checkInstallPath();
        return view('install/home');
    }

    public function install()
    {
        $this->checkInstallPath();
        if($this->request->getPost('do') == 'submit'){
            return $this->checkSetup();
        }
        return view('install/install');
    }

    private function checkSetup()
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
        $db = Database::connect();
        try {
            $db->connect();
        }catch (\Exception $exception){
            $db_error = $db->error();
            $error_msg[] = 'DB: ['.$db_error['code'].'] '.$db_error['message'];
        }


        if(isset($error_msg)){
            return view('install/install_error',[
                'error_msg' => $error_msg
            ]);
        }else{
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
                    $dbFilePath = $this->installPath.'db.sql';
                    if(!file_exists($dbFilePath)){
                        $error_msg = lang_replace('Install.error.file', ['%file%' => $dbFilePath]);
                    }else{
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
                        return redirect()->route('install_complete');
                    }
                }
            }
            return view('install/install_setup',[
                'error_msg' => isset($error_msg) ? $error_msg : null
            ]);
        }
    }

    public function installComplete()
    {
        $this->checkInstallPath();
        return view('install/install_complete',[
            'installPath' => $this->installPath
        ]);
    }
}