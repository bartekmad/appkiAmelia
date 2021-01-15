<?php namespace app\controllers;

use app\forms\CalcForm;
use core\App;
use core\SessionUtils;

class WyswietlanieCtrl
{
    private $form;
    private $result;
    private $listaPojazdow;
    
    public function __construct()
    {
        $this->form = new CalcForm();
        $this->listaPojazdow;
    }
    
    public function action_wyswietl()
    {
        $this->wypelnijListePojazdow();
        $this->pobierzDane();
        $this->generujWidok();
    }
    
    private function pobierzDane()
    {
        $this->form->idPojazdu = isset($_REQUEST['idPojazdu']) ? $_REQUEST['idPojazdu'] : null;
        $this->result = App::getDB()->select("DANE_TANKOWAN", "*", ["ID_POJAZDU" => $this->form->idPojazdu], ["ORDER"=>["ID" => "ASC"]]);
    }
    
    private function wypelnijListePojazdow()
    {
        $idZalogowanegoUzytkownika = intval(SessionUtils::load("user",true)['ID_UZYTKOWNIKA']);
            $this->listaPojazdow = App::getDB()->select("POJAZDY", [
                "ID_POJAZDU",
                "MARKA_POJAZDU",
                "MODEL_POJAZDU"
            ],
            [
                "ID_UZYTKOWNIKA"=>$idZalogowanegoUzytkownika
            ]);
    }
    
    private function generujWidok()
    {
        App::getSmarty()->assign('page_title','Kalkulator spalania - wyświetlanie danych');
        App::getSmarty()->assign('form',$this->form);
        App::getSmarty()->assign('result',$this->result);
        App::getSmarty()->assign('listaPojazdow',$this->listaPojazdow);
        
        App::getSmarty()->display('wyswietlanie.tpl');
    }
}