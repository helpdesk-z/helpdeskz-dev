<?php
/**
 * @package EvolutionScript
 * @author: EvolutionScript S.A.C.
 * @Copyright (c) 2010 - 2020, EvolutionScript.com
 * @link http://www.evolutionscript.com
 */

namespace App\Controllers;

class MailFetcher extends BaseController
{
    public function imap()
    {
        $mailFetcherLib = new \App\Libraries\MailFetcher();
        return $mailFetcherLib->parse_imap();
    }

    public function pipe()
    {
        $mailFetcherLib = new \App\Libraries\MailFetcher();
        return $mailFetcherLib->parse_pipe();
    }
}