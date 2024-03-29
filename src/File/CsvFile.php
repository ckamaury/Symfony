<?php

namespace CkAmaury\Symfony\File;

use Exception;

class CsvFile extends File {

    protected int $row_current_number = 0;
    protected string $delimiter = ';';

    public function setDelimiter(string $delimiter): self{
        $this->delimiter = $delimiter;
        return $this;
    }

    //####### WRITER #######
    public function putDataWithHeaders(array $data){
        array_unshift($data,$this->extractHeaders($data));
        $this->putData($data);

    }
    public function putData(array $data){
        $this->openWriter();
        foreach ($data as $row) {
            $this->putRow($row);
        }
        $this->closeWriter();
    }

    private function putRow(array $row){
        fputcsv($this->fileputter,$row,$this->delimiter);
    }
    private function extractHeaders(array $data) : array{
        $first_row = $data[array_key_first($data)];
        return array_keys($first_row);
    }

    //####### READER #######
    public function openReader(){
        parent::openReader();
        $this->row_current_number = 0;
    }
    public function getData(?string $delimiter = null) : array{
        if(!is_null($delimiter)){
            $this->setDelimiter($delimiter);
        }
        $this->openReader();
        $data = array();
        while( ($row = $this->getNextRow()) !== FALSE ){
            $data[] = $row;
        }
        return $data;
    }
    public function getNextRow(){
        $row = fgetcsv($this->handle, 0, $this->delimiter);
        if($row !== FALSE){
            $this->sanitizeRow($row);
            $this->row_current_number++;
        }
        else{
            $this->closeReader();
        }
        return $row;
    }
    public function getRow(int $rowNumber){
        if($rowNumber < 1){
            throw New Exception("Row Number must be >= 1");
        }

        if($this->row_current_number >= $rowNumber){
            $this->openReader();
        }

        do{
            $row = $this->getNextRow();
        }while($this->row_current_number != $rowNumber && $row !== FALSE);

        return $row;
    }

    private function sanitizeRow(array &$row){
        foreach($row as &$value){
            $value = trim($value);
            $value = ($value != '') ? $value : null;
        }
    }
}