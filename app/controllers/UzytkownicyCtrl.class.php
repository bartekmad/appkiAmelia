<?php namespace app\controllers;

use app\forms\UzytkownicyForm;
use core\App;
use core\SessionUtils;
use core\Message;

class UzytkownicyCtrl {
    
    private $form;
    
    public function __construct()
    {
        $this->form = new UzytkownicyForm();
    }
    
    public function action_zarzadzajUzytkownikami()
    {
        $this->generujWidok();
    }
    
    private function generujWidok()
    {
        App::getSmarty()->assign('page_title','Kalkulator spalania - zarządzanie użytkownikami');
        App::getSmarty()->assign('form',$this->form);
        
        App::getSmarty()->display('uzytkownicy.tpl');
    }
}
