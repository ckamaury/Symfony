<?php

namespace CkAmaury\Symfony\Master;

class Action {

    public const SUCCESS = true;
    public const ERROR = false;

    public array $successMessages = [];
    public array $errorMessages = [];

    public function getSuccessMessages(): array {
        return $this->successMessages;
    }
    public function addSuccessMessage(string $message): self {
        $this->successMessages[] = $message;
        return $this;
    }

    public function getErrorMessages(): array {
        return $this->errorMessages;
    }
    public function addErrorMessage(string $message): self {
        $this->errorMessages[] = $message;
        return $this;
    }





}