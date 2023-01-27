<?php

namespace CkAmaury\Symfony\Master;

use CkAmaury\Symfony\APP;
use CkAmaury\Symfony\Database\Database;

class Master {

    public array $rejectedMessages = [];

    public function getRejectedMessages(): array {
        return $this->rejectedMessages;
    }
    private function addRejectedMessage(string $message): self {
        $this->rejectedMessages[] = $message;
        return $this;
    }
    private function addRejectedMessageAction(): void {
        $this->rejectedMessages[] = 'Erreur lors des actions demandées. Merci de contacter un administrateur.';
    }
    private function addRejectedMessageFlush(): void {
        $this->rejectedMessages[] = 'Erreur lors de la sauvegarde en base de données. Merci de contacter un administrateur.';
    }
    private function addRejectedMessages(array $messages): self {
        $this->rejectedMessages = $messages;
        return $this;
    }
    private function clearRejectedMessages():self{
        $this->rejectedMessages = [];
        return $this;
    }

    private function flush():bool{
        $env = APP::getKernel()->getEnvironment();
        if($env === 'dev') Database::flush();
        else{
            try{Database::flush();}
            catch(\ErrorException $e){
                $this->addRejectedMessageFlush();
                return FALSE;
            }
        }
        return TRUE;
    }
}