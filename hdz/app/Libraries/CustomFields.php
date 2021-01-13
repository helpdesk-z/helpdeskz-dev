<?php
/**
 * @package EvolutionScript
 * @author: EvolutionScript S.A.C.
 * @Copyright (c) 2010 - 2020, EvolutionScript.com
 * @link http://www.evolutionscript.com
 */

namespace App\Libraries;


use Config\Services;

class CustomFields
{
    public function parseForm($customField)
    {
        $code = '<div class="form-group">';
        $code .= '<label>'.$customField->title.' '.($customField->required == 1 ? '<span class="text-danger">*</span>' : '').'</label>';
        switch ($customField->type){
            case 'text':
            case 'password':
            case 'email':
            case 'date':
                $code .= $this->textBox($customField);
                break;
            case 'textarea':
                $code .= $this->textarea($customField);
                break;
            case 'checkbox':
                $code .= $this->checkbox($customField);
                break;
            case 'radio':
                $code .= $this->radio($customField);
                break;
            case 'select':
                $code .= $this->select($customField);
                break;
        }
        $code .= '</div>';
        return $code;
    }

    private function textBox($customField)
    {
        $request = Services::request();
        if(isset($request->getPost('custom')[$customField->id])){
            $default = esc($request->getPost('custom')[$customField->id]);
        }else{
            $default = (in_array($customField->type, ['text','password']) ? $customField->value : '');
        }
        return '<input name="custom['.$customField->id.']" type="'.$customField->type.'" value="'.$default.'" class="form-control" '.($customField->required == 1 ? 'required' : '').'>';
    }

    private function textarea($customField)
    {
        $request = Services::request();
        if(isset($request->getPost('custom')[$customField->id])){
            $default = esc($request->getPost('custom')[$customField->id]);
        }else{
            $default = $customField->value;
        }
        return  '<textarea  name="custom['.$customField->id.']" class="form-control" '.($customField->required == 1 ? 'required' : '').'>'.$default.'</textarea>';
    }


    private function checkbox($customField)
    {
        $code = '';
        $options = explode("\n", $customField->value);
        $request = Services::request();
        if(isset($request->getPost('custom')[$customField->id]) && is_array($request->getPost('custom')[$customField->id])){
            $default = $request->getPost('custom')[$customField->id];
        }else{
            $default = array();
        }
        foreach ($options as $k => $v)
        {
            $checked = in_array($k, $default) ? 'checked' : '';
            $code .= '<div class="custom-control custom-checkbox">';
            $code .= '<input type="checkbox" class="custom-control-input" id="custom'.$customField->id.'_'.$k.'" name="custom['.$customField->id.'][]" value="'.$k.'" '.$checked.'>';
            $code .= '<label class="custom-control-label" for="custom'.$customField->id.'_'.$k.'">'.$v.'</label>';
            $code .= '</div>';
        }
        return $code;
    }

    private function radio($customField)
    {
        $code = '';
        $options = explode("\n", $customField->value);
        $request = Services::request();
        if(isset($request->getPost('custom')[$customField->id])){
            $default = esc($request->getPost('custom')[$customField->id]);
        }else{
            $default = '';
        }
        foreach ($options as $k => $v)
        {
            $checked = ($k == $default) ? 'checked' : '';
            $code .= '<div class="custom-control custom-radio">';
            $code .= '<input type="radio" class="custom-control-input" id="custom'.$customField->id.'_'.$k.'" name="custom['.$customField->id.']" value="'.$k.'" '.$checked.'>';
            $code .= '<label class="custom-control-label" for="custom'.$customField->id.'_'.$k.'">'.$v.'</label>';
            $code .= '</div>';
        }
        return $code;
    }

    private function select($customField)
    {
        $request = Services::request();
        if(isset($request->getPost('custom')[$customField->id])){
            $default = esc($request->getPost('custom')[$customField->id]);
        }else{
            $default = '';
        }
        $options = explode("\n", $customField->value);
        $code = '<select name="custom['.$customField->id.']" class="custom-select">';
        foreach ($options as $k => $v){
            if($default == $k){
                $code .= '<option value="'.$k.'" selected>'.$v.'</option>';
            }else{
                $code .= '<option value="'.$k.'">'.$v.'</option>';
            }
        }
        $code .= '</select>';
        return $code;
    }
}