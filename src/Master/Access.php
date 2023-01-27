<?php

namespace CkAmaury\Symfony\Master;

class Access {

    public const AUTHORIZED = true;
    public const REJECTED = false;

    public array $rejectedMessages = [];

    public function getRejectedMessages(): array {
        return $this->rejectedMessages;
    }
    public function addRejectedMessage(string $message): void {
        $this->rejectedMessages[] = $message;
    }
    public function clearRejectedMessages(): void {
        $this->rejectedMessages = [];
    }

    public function requestNewAccess():void{
        $this->clearRejectedMessages();
    }

    private function hasRejectedMessages():bool{
        return !empty($this->rejectedMessages);
    }

    public function isAuthorized():bool{
        return  !$this->hasRejectedMessages();
    }
    public function isRejected():bool{
        return  !$this->isAuthorized();
    }


}