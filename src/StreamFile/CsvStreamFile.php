<?php

namespace CkAmaury\Symfony\StreamFile;

use CkAmaury\Symfony\Utils\ArrayUtils;

class CsvStreamFile extends StreamFile {

    public function getArrayContent(string $separator = ',', bool $firstLineAsHeader = false):array{
        $stream = $this->getStream();
        $data = [];
        while (($row = fgetcsv($stream, null, $separator)) !== FALSE) {
            $data[] = $row;
        }
        $this->closeStream();
        if($firstLineAsHeader) $this->convertFirstLineAsHeader($data);
        return $data;
    }

    private function convertFirstLineAsHeader(array &$array): void {
        ArrayUtils::first_line_is_header($array);
    }


}