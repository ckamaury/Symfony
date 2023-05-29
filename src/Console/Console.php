<?php

namespace CkAmaury\Symfony\Console;

use CkAmaury\Symfony\APP;
use Symfony\Component\Console\Output\OutputInterface;

class Console {

    public static function write(string $message):void{
        self::getOutputConsole()?->writeln($message);
    }
    private static function getOutputConsole():?OutputInterface{
        return APP::getOutputConsole();
    }

}