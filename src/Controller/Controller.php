<?php

namespace CkAmaury\Symfony\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

abstract class Controller extends AbstractController {

    protected Request $request;

    protected function checkFormIsValid(FormInterface $form){
        return $form->isSubmitted() && $form->isValid();
    }

    protected function createForm(string $type, $data = null, array $options = []): FormInterface{
        $form = parent::createForm($type, $data, $options);
        $form->handleRequest($this->request);
        return $form;
    }
}