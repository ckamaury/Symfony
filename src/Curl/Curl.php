<?php

namespace CkAmaury\Symfony\Curl;

use Exception;

class Curl{

    protected string $url;
    protected array $options = array();
    protected array $options_post = array();

    protected bool $is_executed = false;
    protected bool $is_initialized = false;
    protected bool $is_json_encoded = false;

    protected $init;
    protected $response;
    protected $info;

    protected CurlHeaders $headers;

    public function __construct(){
        $this->headers = new CurlHeaders();
    }

    public function execute(){
        if(!$this->is_executed){
            $this->initialize();
            $this->sendRequest();
            $this->closeRequest();
            $this->is_executed = true;
        }
        return $this->response;
    }
    public function reset():void{
        $this->is_executed = false;
        $this->is_initialized = false;
        $this->url = '';
        $this->options = array();
        $this->options_post = array();
        $this->init = null;
        $this->response = null;
        $this->info = null;
    }

    public function initialize():void{
        if(is_null($this->init) && !$this->is_initialized){
            $this->init = curl_init($this->url);
            curl_setopt_array($this->init,$this->getFullOptions());
            $this->is_initialized = true;
        }
    }
    protected function sendRequest():void{
        $this->response = curl_exec($this->init);
        if ($this->response === false) {
            throw new Exception(curl_error($this->init), curl_errno($this->init));
        }
        $this->info = curl_getinfo($this->init);
    }
    protected function closeRequest():void{
        curl_close($this->init);
        $this->init = null;
    }

    public function setUrl(string $url):self{
        $this->url = $url;
        return $this;
    }
    public function setOptions(array $options):self{
        $this->options = $options;
        return $this;
    }
    public function setIsJsonPostEncoded(bool $value):self{
        $this->is_json_encoded = $value;
        return $this;
    }
    public function addPost(array $post):self{
        $this->options_post = array_merge($this->options_post,$post);
        return $this;
    }


    public function getResponse(){
        return $this->response;
    }
    public function getResponseJson(){
        return json_decode($this->response,true);
    }
    public function getUrlFinal(){
        return $this->info['url'];
    }
    public function getInfo(){
        return $this->info;
    }
    public function getInit(){
        return $this->init;
    }
    public function getFullOptions():array{
        $options = array();
        $options += $this->getOptionsDefault();
        $options += $this->getOptionsPost();
        $options += $this->options;
        $headers = $this->headers->getHeadersFormatted();
        if(!empty($headers)){
            $options[CURLOPT_HTTPHEADER] = $headers;
        }
        return $options;
    }
    public function getOptionsDefault():array{
        return array(
            CURLOPT_RETURNTRANSFER  => TRUE,
            CURLOPT_HEADER          => FALSE,
            CURLOPT_FOLLOWLOCATION  => TRUE,
            CURLOPT_ENCODING        => 'gzip,deflate',
            CURLOPT_USERAGENT       => 'RF/1.0',
            CURLOPT_SSL_VERIFYPEER  => FALSE,
            CURLOPT_SSL_VERIFYHOST  => FALSE,
            CURLOPT_AUTOREFERER     => TRUE,
            CURLOPT_CONNECTTIMEOUT  => 120,
            CURLOPT_TIMEOUT         => 120,
            CURLOPT_MAXREDIRS       => 10
        );
    }
    public function getOptionsPost():array{
        if(!empty($this->options_post)){
            return array(
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => ($this->is_json_encoded)
                    ? json_encode($this->options_post)
                    : http_build_query($this->options_post)
            );
        }
        return array();
    }


    public function addHeader($index,$value):self{
        $this->headers->setValue($index,$value);
        return $this;
    }
    public function addHeaders(array $headers):self{
        foreach($headers as $index => $value){
            $this->headers->setValue($index,$value);
        }
        return $this;
    }

}