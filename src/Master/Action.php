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

    protected function addOneMessage(string $message):void{
        $this->messages[] = $message;
    }
    protected function rejectWithMessage(string $message):void{
        $this->setItIsFailure();
        $this->addOneMessage($message);
    }
    protected function flush():self{
        if($this->isSuccess()){
            try{
                Database::flush();
            }
            catch(\Error|\Exception $e){
                $this->rejectWithError($e);
            }
        }
        return $this->destroyAccess();
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
    protected function isSuccess():bool{
        return $this->isSuccess;
    }
    protected function destroyAccess():self{
        $this->messages = array_merge($this->getAccess()->getMessages(),$this->messages);
        unset($this->access);
        return $this;
    }

    public function setItIsSuccess(): self {
        $this->isSuccess = TRUE;
        return $this;
    }
    public function setItIsFailure(): self {
        $this->isSuccess = FALSE;
        return $this;
    }
    public function succeeded():bool{
        return $this->getAccess()->granted() && $this->isSuccess();
    }
    public function failure():bool{
        return !$this->succeeded();
    }
    public function getMessages(): array {
        return $this->messages;
    }
}