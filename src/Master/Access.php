<?php

namespace CkAmaury\Symfony\Master;

abstract class Access {

    private bool $hasAccess = FALSE;

    protected function authorizedAccess(): void {
        $this->hasAccess = TRUE;
    }

    public function granted(): bool {
        return $this->hasAccess;
    }
    public function rejected(): bool {
        return !$this->granted();
    }

}