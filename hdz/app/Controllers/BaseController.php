<?php
namespace App\Controllers;

/**
 * Class BaseController
 *
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 * Extend this class in any new controllers:
 *     class Home extends BaseController
 *
 * For security be sure to declare any new methods as protected or private.
 *
 * @package CodeIgniter
 */

use App\Libraries\Client;
use App\Libraries\Settings;
use App\Libraries\Staff;
use CodeIgniter\Controller;
use CodeIgniter\Session\Session;
use Config\Services;

class BaseController extends Controller
{

	/**
	 * An array of helpers to be loaded automatically upon
	 * class instantiation. These helpers will be available
	 * to all other controllers that extend BaseController.
	 *
	 * @var array
	 */
	protected $helpers = ['cookie','form','html','helpdesk','number','filesystem','text'];
    /**
     * @var $session Session
     */
    protected $session;
    /**
     * @var $settings Settings
     */
    protected $settings;
    /**
     * @var $staff Staff
     */
    protected $staff;
    /**
     * @var $client Client
     */
    protected $client;


	/**
	 * Constructor.
	 */
	public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
	{

		// Do Not Edit This Line
		parent::initController($request, $response, $logger);
		//--------------------------------------------------------------------
		// Preload any models, libraries, etc, here.
		//--------------------------------------------------------------------
		// E.g.:
		// $this->session = \Config\Services::session();
        if(!defined('INSTALL_ENVIRONMENT')){
            $this->client = Services::client();
            $this->settings = Services::settings();
            $this->session = Services::session();
            $this->staff = Services::staff();
            if($this->settings->config('maintenance') == 1){
                if(!$this->staff->isOnline()){
                    die();
                }
            }

        }
	}

}
