<?php

namespace CkAmaury\Symfony;

use CkAmaury\PhpDatetime\DateTime;
use CkAmaury\Spreadsheet\File;
use CkAmaury\Symfony\Console\Console;
use CkAmaury\Symfony\Memory\Memory;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;
use Symfony\Component\Mime\FileinfoMimeTypeGuesser;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class APP {

    private static BaseKernel $kernel;
    private static ?OutputInterface $outputConsole;

    public static bool $isInitialized = false;
    public static $user;

    private static ?string $dir = null;

    public static function initialize(?BaseKernel $kernel = null,?OutputInterface $output = null):BaseKernel{
        if(!self::$isInitialized){
            setlocale(LC_TIME, "french");
            date_default_timezone_set( 'UTC');
            define('DB_TIME',(new DateTime())->getTimestamp());

            if(!is_null($kernel)) self::initializeKernel($kernel);

            self::$outputConsole = $output;
            self::$isInitialized = TRUE;
            Console::write('APP IS INITIALIZED');
            Console::write('MEMORY LIMIT : '.Memory::getMemoryLimit());
        }
        return self::$kernel;
    }

    public static function initializeKernel(BaseKernel $kernel):void{
        Request::enableHttpMethodParameterOverride();
        self::$kernel = $kernel;
        self::$kernel->boot();
        Console::write('KERNEL IS INITIALIZED');
    }


    public static function getDB_Time():DateTime{
        return (new DateTime())->setTimestamp(DB_TIME);
    }


    public static function setDir(string $dir,int $level = 0): void {
        $dir = str_replace('\\','/',$dir);
        self::$dir = self::upDir($dir,$level);
    }
    public static function getDir(int $level = 0) : string{
        if(is_null(self::$dir)){
            self::setDir(self::getKernel()->getProjectDir());
        }
        return self::upDir(self::$dir,$level).'/';
    }
    private static function upDir(string $dir,int $level):string{
        if($level > 0){
            $array = explode('/',$dir);
            while(count($array) > 0 && $level > 0){
                array_pop($array);
                $level--;
            }
            $dir = implode('/',$array);
        }
        return $dir;
    }

    public static function getUser(){
        return self::$user;
    }
    public static function setUser($user){
        self::$user = $user;
    }


    public static function getKernel():BaseKernel{
        return self::$kernel;
    }
    public static function getContainer():ContainerInterface{
        return self::getKernel()->getContainer();
    }
    public static function getRouter(){
        return self::getContainer()->get('router');
    }





    public static function download(File $file,?bool $deleteAfterSend = false):BinaryFileResponse{
        $mimeTypeGuesser = new FileinfoMimeTypeGuesser();
        $mimeType = ($mimeTypeGuesser->isGuesserSupported()) ? $mimeTypeGuesser->guessMimeType($file->getPath()) : 'text/plain';

        $response = new BinaryFileResponse($file->getPath());
        $response->headers->set('Content-Type', $mimeType);
        $response->setContentDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            $file->getName()
        );
        return $response->deleteFileAfterSend($deleteAfterSend);
    }

    public static function getJsonResponse($p_Connected,$p_Authorized,$p_Error,$p_Values):array{
        return array(
            'connected'     => $p_Connected,
            'authorized'    => $p_Authorized,
            'error'         => $p_Error,
            'values'        => $p_Values
        );
    }

    public static function transformDBResult($p_Array,$p_Index,$p_Index2 = null){
        $array = array();
        foreach($p_Array as $v){
            if(is_null($p_Index2)){
                $array[$v->{$p_Index}()] = $v;
            }
            else{
                $array[$v->{$p_Index}()->{$p_Index2}()] = $v;
            }
        }
        return $array;
    }

    public static function convertDBResult($p_Array){
        return self::transformDBResult($p_Array,'getId');
    }

    public static function generateRandomString($length = 10) : string{
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[Rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public static function generateUrl(string $route, array $parameters = [], int $referenceType = UrlGeneratorInterface::ABSOLUTE_PATH): string {
        return self::getRouter()->generate($route, $parameters, $referenceType);
    }


    public static function getOutputConsole():?OutputInterface{
        return static::$outputConsole;
    }
}

