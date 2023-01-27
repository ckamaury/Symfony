<?php

namespace CkAmaury\Symfony\Master;

use CkAmaury\Symfony\APP;
use CkAmaury\Symfony\Database\Database;

abstract class Action {

    protected Access $access;

    private bool $isSuccess = FALSE;
    private array $messages = [];

    abstract protected function getAccess();
    abstract protected function createAccess();

    public function setIsSuccess(bool $isSuccess): self {
        $this->isSuccess = $isSuccess;
        return $this;
    }

    protected function addOneMessage(string $message):void{
        $this->messages[] = $message;
    }
    protected function rejectWithMessage(string $message):void{
        $this->setIsSuccess(FALSE);
        $this->addOneMessage($message);
    }
    protected function flush():void{
        try{
            Database::flush();
        }
        catch(\Error|\Exception $e){
            $this->rejectWithError($e);
        }
    }
    protected function rejectWithError(\Error|\Exception $error):void{
        $this->rejectWithMessage("Erreur logicielle, merci de contacter l'administrateur.");
        if($this->isDevEnv()) throw new \ErrorException($error->getMessage());
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