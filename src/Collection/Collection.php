<?php


namespace CkAmaury\Symfony\Collection;


class Collection  {

    private $array = array();
    private $NULL = null;

    public function setArray($array){
        $this->array = $array;
    }

    /**
     * @param $value
     * @param null $index
     */
    public function add($value,$index = null) : self{
        if(is_null($index)) $this->array[] = $value;
        else $this->array[$index] = $value;
        return $this;
    }

    public function addIfNotExist($value,$index) : self{
        if(!isset($this->array[$index])){
            $this->add($value,$index);
        }
        return $this;
    }

    public function dump() {
        dd($this->array);
    }

    public function exist($index){
        return (isset($this->array[$index]));
    }

    public function getKeys(){
        return array_keys($this->array);
    }

    public function getValue($index){
        return (isset($this->array[$index])) ? $this->array[$index] : $this->NULL;
    }

    public function getArray(){
        return $this->array;
    }

    public function extract($column) : array{
        $return = array();
        foreach($this->array as $value){
            if(is_array($value)){
                $return[] = $value[$column];
            }
            elseif(is_object($value)){
                $return[] = $value->{$column}();
            }
        }
        return $return;
    }

}