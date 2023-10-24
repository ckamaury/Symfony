<?php

namespace CkAmaury\Symfony\Console;

use CkAmaury\Symfony\APP;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Output\OutputInterface;

class Console {

    public static function write(string $message):void{
        self::getOutputConsole()?->writeln($message);
    }
    private static function getOutputConsole():?OutputInterface{
        return APP::getOutputConsole();
    }


    public static function exitWithError(string $message):int{
        self::write('<error>                                          </error>');
        self::write($message);
        self::write('<error>                                          </error>');
        return Command::FAILURE;
    }
    public static function exitWithSuccess(string $message):int{
        self::write('<bg=green>                                          </>');
        self::write($message);
        self::write('<bg=green>                                          </>');
        return Command::SUCCESS;
    }

}