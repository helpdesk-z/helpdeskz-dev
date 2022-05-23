<?php
/**
 * @package EvolutionScript
 * @author: EvolutionScript S.A.C.
 * @Copyright (c) 2010 - 2020, EvolutionScript.com
 * @link http://www.evolutionscript.com
 */
namespace App\Models;
use CodeIgniter\Model;

class TicketNotesModel extends Model
{
    protected $table      = 'ticket_notes';
    protected $primaryKey = 'id';

    protected $returnType     = 'object';
    protected $useSoftDeletes = false;

    protected $allowedFields = [
    ];
    protected $protectFields = false;
    protected $useTimestamps = false;
    protected $skipValidation = true;
}