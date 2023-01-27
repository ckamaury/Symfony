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

    protected function checkTokenIsValid(string $name):bool{
        return $this->isCsrfTokenValid($name, $this->request->request->get('_token'));
    }

    protected function setRequest(Request $request){
        $this->request = $request;
    }
    public function getRequest():Request{
        return $this->request;
    }

    protected function addSuccessFlash(string $message):void{
        $this->addFlash('success',$message);
    }
    protected function addDangerFlash(string $message):void{
        $this->addFlash('danger',$message);
    }
    protected function addInfoFlash(string $message):void{
        $this->addFlash('info',$message);
    }
    protected function addWarningFlash(string $message):void{
        $this->addFlash('warning',$message);
    }

    protected function addSuccessFlashes(array $messages):void{
        foreach($messages as $message){
            $this->addSuccessFlash($message);
        }
    }
    protected function addDangerFlashes(array $messages):void{
        foreach($messages as $message){
            $this->addDangerFlash($message);
        }
    }
    protected function addInfoFlashes(array $messages):void{
        foreach($messages as $message){
            $this->addInfoFlash($message);
        }
    }
    protected function addWarningFlashes(array $messages):void{
        foreach($messages as $message){
            $this->addWarningFlash($message);
        }
    }

}