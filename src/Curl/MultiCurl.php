<?php

namespace CkAmaury\Symfony\Curl;

class MultiCurl{

    private array $array_curl = array();
    private $multi_curl;

    public function __construct() {
        $this->multi_curl = curl_multi_init();
    }

    public function add($p_Name,$p_Curl){
        if(isset($this->array_curl[$p_Name])){
            if(!is_array($this->array_curl[$p_Name])){
                $this->array_curl[$p_Name] = array($this->array_curl[$p_Name]);
            }
            $this->array_curl[$p_Name][] = $p_Curl;
        }
        else{
            $this->array_curl[$p_Name] = $p_Curl;
        }
        curl_multi_add_handle($this->multi_curl,$p_Curl);
    }
    public function execute(){
        do{
            $status = curl_multi_exec($this->multi_curl, $active);
            if($active) {
                curl_multi_select($this->multi_curl);
            }
        }while($active && $status == CURLM_OK);

        foreach($this->array_curl as $curl){
            if(is_array($curl)){
                foreach($curl as $curl2){
                    curl_multi_remove_handle($this->multi_curl,$curl2);
                }
            }
            else{
                curl_multi_remove_handle($this->multi_curl,$curl);
            }

        }
        curl_multi_close($this->multi_curl);
    }
    public function response(){
        $return = array();
        foreach($this->array_curl as $key => $curl){
            if(is_array($curl)){
                $return[$key] = array();
                foreach($curl as $curl2){
                    $return[$key][] = curl_multi_getcontent($curl2);
                }
            }
            else{
                $return[$key] = curl_multi_getcontent($curl);
            }
        }
        return $return;
    }
    public function responseJson(){
        $return = array();

        foreach($this->response() as $key => $value){
            if(is_array($value)){
                $return[$key] = array();
                foreach($value as $value2){
                    $return[$key][] = json_decode($value2,TRUE);
                }
            }
            else{
                $return[$key] = json_decode($value,TRUE);
            }

        }
        return $return;
    }


}