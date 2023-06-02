<?php

namespace CkAmaury\Symfony\Utils;

use Exception;

class ArrayUtils {

    static function get($key,$array){
        if(!is_array($array)){
            return null;
        }
        if(is_null($key)){
            return null;
        }
        return (isset($array[$key])) ? $array[$key] : NULL;
    }

    static function get_first(array $array){
        $key = array_key_first($array);
        return (isset($array[$key])) ? $array[$key] : NULL;
    }

    static function get_last(array $array){
        $key = array_key_last($array);
        return (isset($array[$key])) ? $array[$key] : NULL;
    }

    static function replace_key($old_key, $new_key, array &$array) {
        $value = $array[$old_key];
        $array[$new_key] = $value;
        unset($array[$old_key]);
    }

    static function replace_keys(array $old_keys,array $new_keys, array &$array) {
        foreach($old_keys as $key => $old_key){
            $new_key = self::get($key,$new_keys);
            self::replace_key($old_key, $new_key,$array);
        }
    }

    /**
     * @note SafeMode use memory but using copy of array to avoid errors
     * @throws Exception
     */
    static function change_keys(array $new_keys, array &$array, bool $safeMode = false) {

        if(count($new_keys) == count($array)) {
            if($safeMode) {
                $buffer = array();
                foreach($array as $old_key => $value){
                    $new_key = array_shift($new_keys);
                    $buffer[$new_key] = $value;
                    unset($array[$old_key]);
                }
                $array = $buffer;
            }
            else{
                foreach($array as $old_key => $value){
                    $new_key = array_shift($new_keys);
                    self::replace_key($old_key,$new_key,$array);
                }
            }
        }
        else{
            throw New Exception("Array & New_Keys have not the same size.");
        }
    }

    static function values_by_key(array $array, $search_key):array{
        $return = array();
        foreach($array as $key => $value){
            if(is_array($value) && isset($value[$search_key])){
                $return[$key] = $value[$search_key];
            }
        }
        return $return;
    }

    static function first_line_is_header(array &$array){

        $headers = array_shift($array);

        $old_keys = array_keys($headers);
        $new_keys = array_values($headers);

        foreach($array as &$row){
            self::replace_keys($old_keys,$new_keys,$row);
        }
    }

    static function switchPositionItems(array &$array,$key_1,$key_2):void{
        [$array[$key_1], $array[$key_2]] = [$array[$key_2], $array[$key_1]];
    }
}


