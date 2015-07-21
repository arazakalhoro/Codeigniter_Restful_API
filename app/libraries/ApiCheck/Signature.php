<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Signature  implements CheckInterFace{

    private $_salt;
    private $_ci;
    private $_header;
    private $_error = 400;

    public function __construct(){
        $this->_ci      = get_instance();
        $this->_salt    = $this->_ci->config->item('rest_signature_key');
        $this->_header  = $this->_ci->input->request_headers();
    }

    public function doCheck(){
        $signature = isset($this->_header['Signature'])?$this->_header['Signature']:'';

        if($signature == ''){
            return FALSE;
        }

        $signature_check = $this->generate();
        if($signature != $signature_check){
            return FALSE;
        }
        return TRUE;
    }

    public function setError($error){
        $this->_error = $error;
    }

    public function getError(){
        return $this->_error;
    }

    protected function generate(){
        $parameter = isset($this->_header['token'])?$this->_header['token']:'';

        for($i = 2;$this->_ci->uri->segment($i) != ''; $i++){
            $parameter = $parameter.$this->_ci->uri->segment($i);
        }

        $signature = md5(md5($parameter).$this->_salt);

        return $signature;
    }






}