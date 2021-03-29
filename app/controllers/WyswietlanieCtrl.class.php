<?php namespace app\controllers;

use app\forms\CalcForm;
use core\App;
use core\SessionUtils;
use core\ParamUtils;

class WyswietlanieCtrl
{
    private $form;
    private $result;
    private $listaPojazdow;
    private $rolaUzytkownika;
    private $stronaAktualna;
    private $stronIlosc;
    
    public function __construct()
    {
        $this->form = new CalcForm();
        $this->listaPojazdow;
        $this->rolaUzytkownika;
    }
    
    public function action_wyswietl()
    {
        $this->stronaAktualna = 1;
        SessionUtils::store("stronaAktualna", $this->stronaAktualna);
        $this->wypelnijListePojazdow();
        $this->pobierzDane();
        $this->stronIlosc = App::getDB()->count("DANE_TANKOWAN", ["ID_POJAZDU" => $this->form->idPojazdu]) / 10;
        $this->stronIlosc = intval($this->stronIlosc + 1);
        SessionUtils::store("stronIlosc", $this->stronIlosc);
        App::getSmarty()->assign('stronaAktualna',$this->stronaAktualna);
        App::getSmarty()->assign('stronIlosc',$this->stronIlosc);
        $this->generujWidok();
    }
    
    public function action_nastepnaStrona()
    {
        $this->wypelnijListePojazdow();
        $this->stronaAktualna = SessionUtils::load("stronaAktualna",true) + 1;
        $this->stronIlosc = SessionUtils::load("stronIlosc",true);
        $this->pobierzDane();
        App::getSmarty()->assign('stronaAktualna',$this->stronaAktualna);
        App::getSmarty()->assign('stronIlosc',$this->stronIlosc);
        $this->generujWidok();
    }
    
        public function action_wyswietlanie()
    {
        $this->wypelnijListePojazdow();
        $this->generujWidok();
    }
    
    private function pobierzDane()
    {
        if (ParamUtils::getFromRequest('idPojazdu'))
        {
            $this->form->idPojazdu = ParamUtils::getFromRequest('idPojazdu',true,'Błędne wywołanie aplikacji');
            SessionUtils::store("idPojazdu", $this->form->idPojazdu);
        }

        $offset = ($this->stronaAktualna - 1) * 10;
        $this->result = App::getDB()->select("DANE_TANKOWAN", "*", ["ID_POJAZDU" => SessionUtils::load("idPojazdu",true), "ORDER"=>["ID" => "DESC"], "LIMIT"=>[$offset,10]]);
    }
    
    private function wypelnijListePojazdow()
    {
        $this->rolaUzytkownika = intval(SessionUtils::load("user",true)['ROLA']);
        if ($this->rolaUzytkownika == 1)
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
        else if ($this->rolaUzytkownika == 0)
        {
            $this->listaPojazdow = App::getDB()->select("POJAZDY", [
                "[>]UZYTKOWNICY" => ["ID_UZYTKOWNIKA" => "ID_UZYTKOWNIKA"]
            ],
            [    
                "POJAZDY.ID_POJAZDU",
                "POJAZDY.MARKA_POJAZDU",
                "POJAZDY.MODEL_POJAZDU",
                "UZYTKOWNICY.LOGIN"
            ]);
        }
    }
    
    private function generujWidok()
    {
        App::getSmarty()->assign('page_title','wyświetlanie danych');
        App::getSmarty()->assign('form',$this->form);
        App::getSmarty()->assign('result',$this->result);
        App::getSmarty()->assign('listaPojazdow',$this->listaPojazdow);
        App::getSmarty()->assign('idPojazdu',$this->form->idPojazdu);
        App::getSmarty()->assign('rolaUzytkownika',$this->rolaUzytkownika);
        
        App::getSmarty()->display('wyswietlanie.tpl');
    }
}