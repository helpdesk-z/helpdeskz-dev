<?php
/**
 * @package EvolutionScript
 * @author: EvolutionScript S.A.C.
 * @Copyright (c) 2010 - 2020, EvolutionScript.com
 * @link http://www.evolutionscript.com
 */

namespace App\Libraries;


use Config\Helpdesk;
use Config\Services;

class UploadEditor
{
    private $uploadPath;
    private $allowedFiles = 'png,jpg,jpeg,gif';
    private $maxUploadSize;
    private $per_page = 25;
    private $page;
    private $total_images=0;
    public function __construct()
    {
        $request = Services::request();
        $this->uploadPath = Helpdesk::UPLOAD_PATH;
        $this->maxUploadSize = max_file_size();
        $this->page = $request->getGet('page') ? $request->getGet('page') : 1;
        $this->getImages();
    }

    public function getImages()
    {
        $files = glob($this->uploadPath."/thumbs/*.{". $this->allowedFiles ."}", GLOB_BRACE);
        # sort by date
        usort($files, function ($a, $b) {
            return filemtime($b) - filemtime($a);
        });
        $this->total_images = count($files);
        $offset		= ($this->page-1)*$this->per_page;
        $thumb_files	= array_slice($files, $offset, $this->per_page);
        return $thumb_files;
    }

    public function totalImages()
    {
        return $this->total_images;
    }

    public function allowedFiles()
    {
        return $this->allowedFiles;
    }

    public function pager()
    {
        $pager = Services::pager();
        return $pager->makeLinks($this->page, $this->per_page, $this->totalImages(), 'editor_full');
    }

    public function deleteFile($file)
    {
        @unlink($this->uploadPath.'/'.$file);
        @unlink($this->uploadPath.'/thumbs/'.$file);
    }

    public function uploadFile()
    {
        $request = Services::request();
        if(!$file = $request->getFile('file')){
            return false;
        }
        $newName = $file->getRandomName();
        $imgPath = Helpdesk::UPLOAD_PATH;
        $thumbPath = $imgPath.DIRECTORY_SEPARATOR.'thumbs';
        if(!is_dir($thumbPath)){
            mkdir($thumbPath);
        }
        $file->move($imgPath, $newName);
        $image = Services::image()->withFile($imgPath.DIRECTORY_SEPARATOR.$newName);
        $image->fit(120, 80, 'center')
            ->save($imgPath.DIRECTORY_SEPARATOR.'thumbs'.DIRECTORY_SEPARATOR.$newName);
        return true;
    }
}