<?php
/**
 * @package EvolutionScript
 * @author: EvolutionScript S.A.C.
 * @Copyright (c) 2010 - 2020, EvolutionScript.com
 * @link http://www.evolutionscript.com
 */
namespace App\Filters;
use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Services;

class StaffAuth implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        helper('cookie');
        helper('helpdesk');
        $staff = Services::staff();
        if(!$staff->isOnline()){
            if(!isset($arguments)){
                return redirect()->route('staff_login');
            }elseif($arguments[0] != 'login'){
                return redirect()->route('staff_login');
            }
        }else{
            if(isset($arguments) && $arguments[0] == 'login'){
                return redirect()->route('staff_dashboard');
            }
            set_timezone(($staff->getData('timezone') == '' ? Services::settings()->config('timezone') : $staff->getData('timezone')));
            Services::tickets()->autoCloseTickets();
        }

        /*

        */
        // Do something here
    }

    //--------------------------------------------------------------------

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do something here
    }
}