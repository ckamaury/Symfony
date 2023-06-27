<?php

namespace CkAmaury\Symfony\Master;

use CkAmaury\Symfony\APP;
use CkAmaury\Symfony\Controller\Controller;
use CkAmaury\Symfony\Database\Database;
use Error;
use ErrorException;
use Exception;

abstract class Action {

    protected Access $access;
    private bool $isSuccess = FALSE;
    private bool $isFinished = FALSE;
    private array $messages = [];
    private ?string $successMessage = null;

    abstract protected function createAccess();

    protected function getAccess():Access{
        if(!isset($this->access)) $this->createAccess();
        return $this->access;
    }

    public function __call(string $name, array $arguments):static{
        if(method_exists($this,$name)){
            if($this->getAccess()->{$name}()->granted()){
                if($this->isProdEnv()){
                    try{
                        $this->executeAction($name,$arguments);
                    }
                    catch(Error|Exception $e){
                        $this->rejectWithError($e);
                    }
                }
                else {
                    $this->executeAction($name,$arguments);
                }
            }
            else{
                foreach($this->getAccess()->getMessages() as $message){
                    $this->rejectWithMessage($message);
                }
            }
        }
        else{
            $this->rejectWithMessage("Erreur logicielle, merci de contacter l'administrateur.");
            if($this->isDevEnv()) throw new ErrorException("La fonction n'existe pas");
        }
        return $this;
    }

    private function executeAction(string $name, array $arguments):void{
        $this->{$name}(...$arguments);
        if($this->isSuccess) {
            if(is_null($this->successMessage)){
                $this->successMessage = 'Action validée';
            }
            $this->flush();
        }
    }

    protected function addOneMessage(string $message):void{
        $this->messages[] = $message;
    }
    protected function rejectWithMessage(string $message):void{
        $this->setItIsFailure();
        $this->addOneMessage($message);
    }
    protected function flush():static{
        if($this->isSuccess() && $this->getAccess()->granted()){
            try{
                Database::flush();
            }
            catch(Error|Exception $e){
                $this->rejectWithError($e);
            }
        }
        return $this->setItIsFinished()->destroyAccess();
    }
    protected function rejectWithError(Error|Exception $error):void{
        $this->rejectWithMessage("Erreur logicielle, merci de contacter l'administrateur.");
        if($this->isDevEnv()) throw new ErrorException($error->getMessage());
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

    public function setItIsSuccess(?string $successMessage = null): self {
        $this->isSuccess = TRUE;
        $this->successMessage = $successMessage;
        return $this;
    }
    public function setItIsFailure(): static {
        $this->isSuccess = FALSE;
        return $this;
    }
    public function setItIsFinished(): static {
        $this->isFinished = TRUE;
        return $this;
    }
    public function succeeded(?Controller $controller = null):bool{
        $success = $this->isSuccessAndFinished();
        if(!is_null($controller)){
            if($success) $controller->addSuccessFlash($this->successMessage);
            else $controller->addDangerFlashes($this->messages);
        }
        return $success;
    }
    public function failure(?Controller $controller = null):bool{
        return !$this->succeeded($controller);
    }
    public function getMessages(): array {
        return $this->messages;
    }
    public function getUserSession(){
        return APP::getUser();
    }
}