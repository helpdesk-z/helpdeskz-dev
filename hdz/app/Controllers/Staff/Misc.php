<?php
/**
 * @package EvolutionScript
 * @author: EvolutionScript S.A.C.
 * @Copyright (c) 2010 - 2020, EvolutionScript.com
 * @link http://www.evolutionscript.com
 */

namespace App\Controllers\Staff;


use App\Controllers\BaseController;
use App\Libraries\UploadEditor;
use Config\Services;

class Misc extends BaseController
{
    public function getKB()
    {
        if(is_numeric($this->request->getGet('kb'))){
            $kb = Services::kb();
            if($article = $kb->getArticle($this->request->getGet('kb'), false)){
                return json_encode(['article' => $article->content]);
            }
        }
    }

    public function uploadEditor()
    {
        $uploadEditor = new UploadEditor();
        if($this->request->getPost('do') == 'delete'){
            $csrf_name = csrf_token();
            $csrf_value = csrf_hash();
            $result = ['token_name' => $csrf_name, 'token_value' => $csrf_value];
            if($this->request->getPost('file')){
                $uploadEditor->deleteFile($this->request->getPost('file'));
            }
            return json_encode($result);
        }
        elseif ($this->request->getPost('do') == 'upload'){
            $validation = Services::validation();
            $validation->setRule('file', 'file', 'is_image[file]|max_size[file,'.max_file_size().']');
            if($validation->withRequest($this->request)->run() == false){
                return $this->response->setStatusCode(500)->setBody($validation->listErrors());
            }
            if(!$uploadEditor->uploadFile()){
                return $this->response->setStatusCode(500);
            }

            if(!$file = $this->request->getFile('file')){
                return $this->response->setStatusCode(500);
            }
            if (defined('HDZDEMO')) {
                return 'This is not possible is demo version.';
            }

            $csrf_name = csrf_token();
            $csrf_value = csrf_hash();
            $result = ['token_name' => $csrf_name, 'token_value' => $csrf_value];
            return json_encode($result);
        }


        $allowed_extensions = '.'.$uploadEditor->allowedFiles();
        $allowed_extensions = str_replace(',', ',.', $allowed_extensions);
        return view('staff/tinymce_image_manager',[
            'total_images' => $uploadEditor->totalImages(),
            'thumb_files' => $uploadEditor->getImages(),
            'pagination' => $uploadEditor->pager(),
            'allowed_extensions' => $allowed_extensions,
            'max_upload_size' => max_file_size()
        ]);
    }
}