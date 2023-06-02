<?php

namespace CkAmaury\Symfony\Curl;

class CurlHeaders{

    private array $headers = array();

    public function getHeaders():array{
        return $this->headers;
    }
    public function getHeadersFormatted():array{
        $array = array();
        foreach($this->headers as $index => $value){
            $array[] = $index.':'.$value;
        }
        return $array;
    }

    public function setHeaders(array $headers):self{
        $this->headers = $headers;
        return $this;
    }
    public function setValue($index,$value):self{
        $this->headers[$index] = $value;
        return $this;
    }



}