<?php

namespace CkAmaury\Symfony\Master;

abstract class Access {

    private bool $hasAccess = FALSE;

    private array $messages = [];

    protected function authorizedAccess(): void {
        $this->hasAccess = TRUE;
    }
    protected function addOneMessage(string $message):void{
        $this->messages[] = $message;
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


}