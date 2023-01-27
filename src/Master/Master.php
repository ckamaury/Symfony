<?php

namespace CkAmaury\Symfony\Master;

use CkAmaury\Symfony\APP;
use CkAmaury\Symfony\Database\Database;

abstract class Master {

    public array $rejectedMessages = [];

    abstract protected function getActions();

    public function getRejectedMessages(): array {
        return $this->rejectedMessages;
    }
    protected function addRejectedMessage(string $message): self {
        $this->rejectedMessages[] = $message;
        return $this;
    }
    protected function addRejectedMessageAction(): void {
        $this->rejectedMessages[] = 'Erreur lors des actions demandées. Merci de contacter un administrateur.';
    }
    protected function addRejectedMessageFlush(): void {
        $this->rejectedMessages[] = 'Erreur lors de la sauvegarde en base de données. Merci de contacter un administrateur.';
    }
    protected function addRejectedMessages(array $messages): self {
        $this->rejectedMessages = $messages;
        return $this;
    }
    protected function clearRejectedMessages():self{
        $this->rejectedMessages = [];
        return $this;
    }
    protected function hasRejectedMessages():bool{
        return !empty($this->rejectedMessages);
    }
    protected function hasNoRejectedMessages():bool{
        return !$this->hasRejectedMessages();
    }

    protected function flush():bool{
        if($this->isDevEnv()) Database::flush();
        else{
            try{Database::flush();}
            catch(\ErrorException $e){
                $this->addRejectedMessageFlush();
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

    protected function requestActions(){
        $this->clearRejectedMessages();
        $this->getAccesses()->requestNewAccess();
    }
    protected function finishActions():bool{
        if($this->getAccesses()->isAuthorized()) $this->flush();
        else $this->addRejectedMessages($this->getAccesses()->getRejectedMessages());
        return $this->hasNoRejectedMessages();
    }




}