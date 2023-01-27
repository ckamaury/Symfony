<?php

namespace CkAmaury\Symfony\Master;

class Access {

    public const AUTHORIZED = true;
    public const REJECTED = false;

    public array $rejectedMessages = [];

    public function getRejectedMessages(): array {
        return $this->rejectedMessages;
    }
    public function addRejectedMessage(string $message): self {
        $this->rejectedMessages[] = $message;
        return $this;
    }


}