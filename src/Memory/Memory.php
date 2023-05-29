<?php

namespace CkAmaury\Symfony\Memory;

class Memory {

    public static function setMemoryLimit(string $memory):string|false{
        return ini_set('memory_limit', $memory);
    }

    public static function getMemoryPeak():string{
        $bytes = memory_get_peak_usage();
        $unit=array('B','KB','MB','GB','TB','PB');
        if ($bytes==0) return '0 ' . $unit[0];
        return round($bytes/pow(1000,($i=floor(log($bytes,1000)))),2) .' '. (isset($unit[$i]) ? $unit[$i] : 'B');
    }

}