<?php

namespace CkAmaury\Symfony;

use CkAmaury\Spreadsheet\File;
use DateTime;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Mime\FileinfoMimeTypeGuesser;
use Symfony\Component\Security\Core\Authentication\Token\SwitchUserToken;

class APP {

    public static bool $is_init = false;
    public static $user;

    public static function init(){
        if(self::$is_init == FALSE){
            setlocale(LC_TIME, "french");
            date_default_timezone_set( 'UTC');
            define('DB_TIME',(new DateTime())->getTimestamp());
            self::$is_init = TRUE;
        }
    }

    public static function getDB_Time(){
        return (new DateTime())->setTimestamp(DB_TIME);
    }

    public static function getDir(int $level = 0) : string{
        $dir = self::getKernel()->getProjectDir();
        $dir = str_replace('\\','/',$dir);
        if($level > 0){
            $array = explode('/',$dir);
            while(count($array) > 0 && $level > 0){
                array_pop($array);
                $level--;
            }
            $dir = implode('/',$array);
        }
        return $dir.'/';
    }

    public static function getUser(){
        return self::$user;
    }
    public static function setUser($user){
        self::$user = $user;
    }

    public static function getKernel(){
        global $kernel;
        if ( 'AppCache' == get_class($kernel) ) {
            $kernel = $kernel->getKernel();
        }
        return $kernel;
    }

    public static function getRouter(){
        return self::getKernel()->getContainer()->get('router');
    }

    public static function getManager($base = null): EntityManager{
        if(is_null($base)){
            return self::getKernel()->getContainer()->get('doctrine.orm.entity_manager');
        }
        else{
            return self::getKernel()->getContainer()->get('doctrine')->getManager($base);
        }
    }

    public static function getReference($p_Class,$p_ID) {
        return self::getManager()->getReference($p_Class,$p_ID);
    }

    public static function download(File $file,?bool $deleteAfterSend = false){
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

    public static function getJsonResponse($p_Connected,$p_Authorized,$p_Error,$p_Values){
        return array(
            'connected'     => $p_Connected,
            'authorized'    => $p_Authorized,
            'error'         => $p_Error,
            'values'        => $p_Values
        );
    }

    public static function transformDBResult($p_Array,$p_Index,$p_Index2 = null){
        $array = array();
        foreach($p_Array as $k => $v){
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

}

