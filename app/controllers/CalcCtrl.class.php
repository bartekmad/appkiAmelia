<?php namespace app\controllers;

use app\forms\CalcForm;
use app\transfer\CalcResult;
use core\Message;
use core\SessionUtils;
use core\App;
use core\ParamUtils;

class CalcCtrl
{
    private $form;
    private $result;
    
    public function __construct()
    {
        $this->form = new CalcForm();
        $this->result = new CalcResult();
    }
    
    public function action_wykonaj()
    {
        $this->pobierzParametry();
        if ($this->czyWpisaneWartosci())
        {
            if ($this->waliduj())
            {
                $this->wykonajZadanie();
            }
        }
        $this->action_wprowadzanie();
    }
    
    private function pobierzParametry()
    {
        $this->form->kwotaTankowania = ParamUtils::getFromRequest('kwotaTankowania',true,'Błędne wywołanie aplikacji');
        $this->form->cenaZaLitr = ParamUtils::getFromRequest('cenaZaLitr',true,'Błędne wywołanie aplikacji');
        $this->form->stanPoczatkowy = ParamUtils::getFromRequest('stanPoczatkowy',true,'Błędne wywołanie aplikacji');
        $this->form->dataTankowania = ParamUtils::getFromRequest('dataTankowania',true,'Błędne wywołanie aplikacji');
        $this->form->idPojazdu = ParamUtils::getFromRequest('idPojazdu',true,'Błędne wywołanie aplikacji');
    }

    private function czyWpisaneWartosci()
    {
        return (isset($this->form->kwotaTankowania) && isset($this->form->cenaZaLitr) && isset($this->form->stanPoczatkowy) && isset($this->form->dataTankowania) && isset($this->form->idPojazdu));
    }

    private function waliduj()
    {
        $walidacja = true;

        if ($this->form->kwotaTankowania == "")
        {
            App::getMessages()->addMessage(new Message('Nie podano kwoty tankowania!', Message::ERROR));
            $walidacja = false;
        }
        if ($this->form->cenaZaLitr == "")
        {
            App::getMessages()->addMessage(new Message('Nie podano ceny za litr!', Message::ERROR));
            $walidacja = false;
        }
        if ($this->form->stanPoczatkowy == "")
        {
            App::getMessages()->addMessage(new Message('Nie podano stanu licznika!', Message::ERROR));
            $walidacja = false;
        }
        if ($this->form->dataTankowania == "")
        {
            App::getMessages()->addMessage(new Message('Nie podano daty tankowania!', Message::ERROR));
            $walidacja = false;
        }
        if ($this->form->idPojazdu == "")
        {
            App::getMessages()->addMessage(new Message('Nie wybrano pojazdu!', Message::ERROR));
            $walidacja = false;
        }

        if ($walidacja == false)
        {
            return false;
        }

        $this->form->kwotaTankowania = floatval(str_replace(',', '.', $this->form->kwotaTankowania));
        $this->form->cenaZaLitr = floatval(str_replace(',', '.', $this->form->cenaZaLitr));
        if (!is_float($this->form->kwotaTankowania))
        {
            App::getMessages()->addMessage(new Message('Kwota tankowania powinna być liczbą!', Message::ERROR));
            $walidacja = false;
        }
        if (!is_float($this->form->cenaZaLitr))
        {
            App::getMessages()->addMessage(new Message('Cena za litr powinna być liczbą!', Message::ERROR));
            $walidacja = false;
        }	
        if (!is_numeric($this->form->stanPoczatkowy))
        {
            App::getMessages()->addMessage(new Message('Stan licznika powinna być liczbą!', Message::ERROR));
            $walidacja = false;
        }
        
        $wynik = App::getDB()->select("DANE_TANKOWAN",[
            "STAN_START",
            "DATA"],
            [
            "ID_POJAZDU"=>$this->form->idPojazdu
            ],
            [
            "ORDER"=>["ID" => "DESC"],
            "LIMIT"=>1 
        ]);
        if (count($wynik) > 0)
        {
            if ($this->form->stanPoczatkowy <= $wynik[0]["STAN_START"])
            {
                App::getMessages()->addMessage(new Message('Stan licznika musi być większy od poprzedniego!', Message::ERROR));
                $walidacja = false;
            }
            if ($this->form->dataTankowania < $wynik[0]["DATA"])
            {
                App::getMessages()->addMessage(new Message('Data tankowania nie może być wcześniejsza od poprzedniej!', Message::ERROR));
                $walidacja = false;
            }
        }
        return $walidacja;
    }
    
    
    private function wykonajZadanie(){
        $kwotaTankowania = floatval($this->form->kwotaTankowania);
        $cenaZaLitr = floatval($this->form->cenaZaLitr);
        $stanPoczatkowy = intval($this->form->stanPoczatkowy);
        $dataTankowania = date($this->form->dataTankowania);
        $idPojazdu = intval($this->form->idPojazdu);
       
        $wynik = App::getDB()->select("DANE_TANKOWAN",[
            "ID",
            "STAN_START"],
        [   
            "ORDER"=>["ID" => "DESC"],
            "LIMIT"=>1
        ],[
            "ID_POJAZDU"=>$idPojazdu
        ]);

        try{
            if (count($wynik) > 0)
            {
                if ($stanPoczatkowy > $wynik[0]["STAN_START"])
                {
                    App::getDB()->update("DANE_TANKOWAN",[
                        "STAN_STOP"=>$stanPoczatkowy],
                        [
                        "ID"=>$wynik[0]["ID"]
                    ]);
                }
            }

            App::getDB()->insert("DANE_TANKOWAN", [
                "KWOTA"=>$kwotaTankowania,
                "CENA_LITR"=>$cenaZaLitr,
                "STAN_START"=>$stanPoczatkowy,
                "DATA"=>$dataTankowania,
                "ID_POJAZDU"=>$idPojazdu
            ]);
        }
        catch (PDOException $e)
        {
            App::getMessages()->addMessage(new Message('Wystąpił błąd podczas dodawania rekordu na bazę.', Message::ERROR));
        }
        finally
        {
            App::getMessages()->addMessage(new Message('Pomyślnie dodano rekord na bazę.', Message::INFO));
        }
    }
    
    private function wypelnijListePojazdow()
    {
        $idZalogowanegoUzytkownika = intval(SessionUtils::load("user",true)['ID_UZYTKOWNIKA']);
            $this->result = App::getDB()->select("POJAZDY", [
                "ID_POJAZDU",
                "MARKA_POJAZDU",
                "MODEL_POJAZDU"
            ],
            [
                "ID_UZYTKOWNIKA"=>$idZalogowanegoUzytkownika
            ]);
    }

    public function action_wprowadzanie()
    {
        App::getSmarty()->assign('page_title','wprowadzanie danych');
        App::getSmarty()->assign('form',$this->form);
           
        $this->wypelnijListePojazdow();
            
        App::getSmarty()->assign('result',$this->result);
        
        App::getSmarty()->display("calc.tpl");
    }
}