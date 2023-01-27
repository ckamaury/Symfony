<?php

namespace CkAmaury\Symfony\Master;

use CkAmaury\Symfony\APP;
use CkAmaury\Symfony\Database\Database;

abstract class Action {

    protected Access $access;

    private bool $isSuccess = FALSE;
    private array $messages = [];

    protected function getAccess(){
        return $this->access;
    }

    protected function addOneMessage(string $message):void{
        $this->messages[] = $message;
    }
    protected function flush():bool{
        if($this->isDevEnv()) Database::flush();
        else{
            try{Database::flush();}
            catch(\ErrorException $e){
                $this->addOneMessage('Erreur lors de la sauvegarde en base de données.');
                return FALSE;
            }
        }
        return TRUE;
    }
    protected function isDevEnv():bool{
        return APP::getKernel()->getEnvironment() === 'dev';
    }
    protected function isProdEnv():bool{
        return APP::getKernel()->getEnvironment() === 'prod';
    }

    public function getMessages(): array {
        return array_merge($this->getAccess()->getMessages(),$this->messages) ;
    }
    private function isSuccess():bool{
        return $this->isSuccess;
    }
    public function succeeded():bool{
        return $this->getAccess()->granted() && $this->isSuccess();
    }
    public function failure():bool{
        return !$this->succeeded();
    }

}