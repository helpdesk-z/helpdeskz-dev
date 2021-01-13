<?php
namespace App\Libraries;

use App\Models\Attachments as AttachmentModel;
use CodeIgniter\Files\File;
use CodeIgniter\HTTP\Request;
use Config\Services;

class Attachments
{
    protected $attachmentModel;
    public function __construct()
    {
        $this->attachmentModel = new AttachmentModel();
    }
    public function addFromTicket($ticket_id, $message_id, $original_name, $mask_name, $file_size='', $file_type='')
    {
        $this->attachmentModel->protect(false);
        $this->attachmentModel->insert([
            'name' => $original_name,
            'enc' => $mask_name,
            'filesize' => $file_size,
            'ticket_id' => $ticket_id,
            'msg_id' => $message_id,
            'filetype' => $file_type
        ]);
        $this->attachmentModel->protect(true);
    }

    public function ticketUpload()
    {
        $request = Services::request();
        $settings = Services::settings();
        $files = array();
        for($i=0;$i< $settings->config('ticket_attachment_number');$i++){
            if($attachment = $request->getFile('attachment.'.$i)){
                if($attachment->isValid() && !$attachment->hasMoved()){
                    $name = $attachment->getName();
                    $encoded_name = $attachment->getRandomName();
                    $size = $attachment->getSize();
                    $files[] = [
                        'name' => $name,
                        'encoded_name' => $encoded_name,
                        'size' => $size,
                        'file_type' => $attachment->getMimeType()
                    ];
                    $attachment->move(WRITEPATH.'attachments', $encoded_name, true);
                }
            }
        }
        if(count($files) == 0){
            return null;
        }
        return $files;
    }


    public function addTicketFiles($ticket_id, $message_id, $files)
    {
        $this->attachmentModel->protect(false);
        foreach ($files as $item){
            $this->attachmentModel->insert([
                'name' => $item['name'],
                'enc' => $item['encoded_name'],
                'filesize' => $item['size'],
                'ticket_id' => $ticket_id,
                'msg_id' => $message_id,
                'filetype' => $item['file_type']
            ]);
        }
        $this->attachmentModel->protect(true);
    }

    public function getList($data)
    {
        $q = $this->attachmentModel->where($data)
            ->get();
        if($q->resultID->num_rows == 0){
            return null;
        }
        $r = $q->getResult();
        $q->freeResult();
        return $r;
    }


    public function getRow($data)
    {
        $q = $this->attachmentModel->where($data)
            ->get();
        if($q->resultID->num_rows == 0){
            return null;
        }
        return $q->getRow();
    }

    public function download($file)
    {
        $response = Services::response();
        return $response->download( WRITEPATH.'attachments'.DIRECTORY_SEPARATOR.$file->enc, null)
            ->setFileName($file->name);
    }

    public function deleteFile($file)
    {
        $this->attachmentModel->delete($file->id);
        @unlink(WRITEPATH.'attachments/'.$file->enc);
    }

    public function deleteFiles($data)
    {
        $q = $this->attachmentModel->select('id, enc, article_id, ticket_id')
            ->where($data)
            ->get();
        if($q->resultID->num_rows == 0){
            return false;
        }
        foreach ($q->getResult() as $item){
            @unlink(WRITEPATH.'attachments/'.$item->enc);
        }
        $this->attachmentModel->where($data)
            ->delete();
        return true;
    }
}