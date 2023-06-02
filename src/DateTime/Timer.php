<?php

namespace CkAmaury\Symfony\DateTime;

class Timer{

    private ?float $start;
    private array $steps;

    public function __construct(){
        $this->clean();
    }

    public function start(): void{
        $this->start = microtime(true);
    }
    public function step(): void{
        $this->addEntry();
    }
    public function stop(): float{
        $this->step();
        return $this->getLast()['microtime'] - $this->start;
    }
    public function clean(): void{
        $this->start = null;
        $this->steps = array();
    }

    private function addEntry():void{
        $step = microtime(true);

        $previous = (!is_null($this->getLast())) ? $this->getLast()['microtime'] : $this->start;

        $this->steps[] = [
            'microtime' => $step,
            'diff_start' => round($step - $this->start,4),
            'diff_previous' => round($step - $previous,4),
            'memory' => $this->convertMemory(memory_get_usage(true))
        ];
    }
    private function convertMemory($size):string{
        $unit=array('B','KB','MB','GB','TB','PB');
        return round($size/pow(1024,($i=floor(log($size,1024)))),2).$unit[$i];
    }
    private function getLast():?array{
        $key_previous = array_key_last($this->steps);
        if(!is_null($key_previous)){
            return $this->steps[$key_previous];
        }
        return NULL;
    }

    public function getData(): array{
        return $this->steps;
    }
}