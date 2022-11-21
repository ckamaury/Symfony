<?php

namespace CkAmaury\Symfony\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

abstract class Controller extends AbstractController {

    private Request $request;

    protected function checkFormIsValid(FormInterface $form):bool{
        return $form->isSubmitted() && $form->isValid();
    }
    protected function createForm(string $type, $data = null, array $options = ['method' => 'POST']): FormInterface{
        $form = parent::createForm($type, $data, $options);
        $form->handleRequest($this->request);
        return $form;
    }

    protected function setRequest(Request $request){
        $this->request = $request;
    }
    public function getRequest():Request{
        return $this->request;
    }

}