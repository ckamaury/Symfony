<?php

namespace CkAmaury\Symfony\Utils;

class NumberUtils{

    static function getSuperior($a,$b){
        if(is_null($a)){
            return $b;
        }
        if(is_null($b)){
            return $a;
        }
        return ($a > $b) ? $a : $b;
    }

    static function getInferior($a,$b){
        if(is_null($a)){
            return $b;
        }
        if(is_null($b)){
            return $a;
        }
        return ($a < $b) ? $a : $b;
    }
}