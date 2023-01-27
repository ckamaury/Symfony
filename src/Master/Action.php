<?php

namespace CkAmaury\Symfony\Master;

abstract class Action {

    protected Access $access;

    public function getAccess(): Access {
        return $this->access;
    }



}