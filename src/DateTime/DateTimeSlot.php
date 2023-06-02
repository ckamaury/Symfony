<?php

namespace CkAmaury\Symfony\DateTime;

use DateInterval;
use DateTimeInterface;

class DateTimeSlot {

    private DateTime $start;
    private DateTime $end;

    public function __construct(DateTimeInterface $start,DateTimeInterface $end){
        $this->start = new DateTime();
        $this->end = new DateTime();
        if($start < $end){
            $this->start->init($start);
            $this->end->init($end);
        }
        else{
            $this->start->init($end);
            $this->end->init($start);
        }
    }

    public function union(DateTimeSlot $slot): ?DateInterval{

        if($this->start->isSupOrEqual($slot->getStart())){
            $start = $this->start->clone();
        }
        else{
            $start = $slot->getStart()->clone();
        }

        if($this->end->isInfOrEqual($slot->getEnd())){
            $end = $this->end->clone();
        }
        else{
            $end = $slot->getEnd()->clone();
        }

        if($start < $end){
            return $start->interval($end);
        }
        else{
            return NULL;
        }
    }
    public function unionInMinutes(DateTimeSlot $slot): int{
        $interval = $this->union($slot);
        $minutes = 0;
        if(!is_null($interval)){
            $minutes = $interval->days * 24 * 60;
            $minutes += $interval->h * 60;
            $minutes += $interval->i;
        }
        return $minutes;
    }

    public function getStart(): DateTime{
        return $this->start;
    }
    public function getEnd(): DateTime{
        return $this->end;
    }
}