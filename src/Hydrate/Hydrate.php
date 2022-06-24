<?php


namespace CkAmaury\Symfony\Hydrate;


class Hydrate {

    protected $array = array();

    public function setArray($array){
        $this->array = $array;
    }

    public function getArray(){
        return $this->array;
    }

    public function setValue($value){
        $this->array = array();
        $this->addValue($value);
    }

    public function addValue($value){
        $this->array[] = $value;
    }

    public function getValue(){
        return $this->array[array_key_first($this->array)];
    }

    protected function getIds(){
        $ids = array();
        foreach($this->array as $value){
            $ids[] = $value->getId();
        }
        return $ids;
    }

}