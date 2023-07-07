<?php

namespace CkAmaury\Symfony\StreamFile;

class StreamFile {

    /** @param resource $stream */
    public function __construct(private mixed $stream){}

    protected function closeStream():void{
        fclose($this->getStream());
    }

    public function getContent():string{
        $stream = stream_get_contents($this->getStream());
        $this->closeStream();
        return $stream;
    }
    public function getStream(): mixed {
        return $this->stream;
    }

}