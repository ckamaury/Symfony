<?php

namespace CkAmaury\Symfony\Utils;

class FolderUtils {

    static function scandir(string $directory):array{
        return array_diff(scandir($directory), array('.', '..'));
    }

    static function scandirByOrder(string $directory,$order = "DESC"):array{
        $files = array();
        foreach (self::scandir($directory) as $file) {
            $files[$file] = filemtime($directory . '/' . $file);
        }
        switch($order){
            case 'DESC':
                arsort($files);
                break;
            case 'ASC':
            default:
                asort($files);
                break;
        }
        return array_keys($files);
    }

}