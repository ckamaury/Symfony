<?php

namespace CkAmaury\Symfony\Master;

use CkAmaury\Symfony\APP;
use CkAmaury\Symfony\Controller\Controller;
use CkAmaury\Symfony\Database\Database;

abstract class Action {

    protected Access $access;
    private bool $isSuccess = FALSE;
    private bool $isFinished = FALSE;
    private array $messages = [];
    private string $successMessage;

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
        if($this->isSuccess() && $this->getAccess()->granted()){
            try{
                Database::flush();
            }
            catch(\Error|\Exception $e){
                $this->rejectWithError($e);
            }
        }
        return $this->setItIsFinished()->destroyAccess();
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
    protected function isFinished():bool{
        return $this->isFinished;
    }
    protected function destroyAccess():self{
        $this->messages = array_merge($this->getAccess()->getMessages(),$this->messages);
        unset($this->access);
        return $this;
    }
    protected function isSuccessAndFinished():bool{
        return $this->isFinished() && $this->isSuccess();
    }

    public function setItIsSuccess(?string $successMessage): self {
        $this->isSuccess = TRUE;
        $this->successMessage = $successMessage;
        return $this;
    }
    public function setItIsFailure(): self {
        $this->isSuccess = FALSE;
        return $this;
    }
    public function setItIsFinished(): self {
        $this->isFinished = TRUE;
        return $this;
    }
    public function succeeded(Controller $controller = null):bool{
        $success = $this->isSuccessAndFinished();
        if(!is_null($controller)){
            if($success) $controller->addSuccessFlash($this->successMessage);
            else $controller->addDangerFlashes($this->messages);
        }
        return $success;
    }
    public function failure(Controller $controller = null):bool{
        return !$this->succeeded();
    }
    public function getMessages(): array {
        return $this->messages;
    }
}