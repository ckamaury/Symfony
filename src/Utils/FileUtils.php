<?php

namespace CkAmaury\Symfony\Utils;

class FileUtils {

    public static function getContentCSV(string $content, string $separator = ',', bool $firstLineAsHeader = false):array{
        $data = str_getcsv($content,$separator);
        self::sanitizeData($data);
        if($firstLineAsHeader) ArrayUtils::first_line_is_header($data);
        return $data;
    }

    public static function sanitizeData(array &$data):void{
        foreach($data as &$row){
            self::sanitizeRow($row);
        }
    }
    public static function sanitizeRow(array &$row):void{
        foreach($row as &$value){
            $value = trim($value);
            $value = ($value != '') ? $value : null;
        }
    }

}