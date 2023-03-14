<?php

namespace CkAmaury\Symfony\Master;

use CkAmaury\Symfony\APP;

abstract class Access {

    private bool $hasAccess = FALSE;
    private array $messages = [];

    protected function authorizedAccess(): void {
        $this->hasAccess = TRUE;
    }
    protected function rejectedAccess(): void {
        $this->hasAccess = FALSE;
    }
    protected function addOneMessage(string $message):void{
        $this->messages[] = $message;
    }
    protected function rejectWithMessage(string $message):void{
        $this->rejectedAccess();
        $this->addOneMessage($message);
    }

    public function granted(): bool {
        return $this->hasAccess;
    }
    public function rejected(): bool {
        return !$this->granted();
    }
    public function getMessages(): array {
        return $this->messages;
    }
    public function getUserSession(){
        return APP::getUser();
    }

}