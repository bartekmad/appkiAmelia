<?php namespace app\controllers;

use app\forms\PojazdyForm;
use core\App;
use core\SessionUtils;
use core\Message;
use core\ParamUtils;

class PojazdyCtrl
{
    private $form;
    
    public function __construct()
    {
        $this->form = new PojazdyForm();
    }
    
    public function action_zarzadzajPojazdami()
    {
        $this->generujWidok();
    }
   
    public function action_dodajPojazd()
    {
        $this->pobierzParametry();
        if ($this->czyWpisaneWartosci())
        {
            if ($this->waliduj())
                $this->zapiszDaneNaBaze();
        }
        $this->generujWidok();   
    }
    
    private function pobierzParametry()
    {
        $this->form->marka = ParamUtils::getFromRequest('marka',true,'Błędne wywołanie aplikacji');
        $this->form->model = ParamUtils::getFromRequest('model',true,'Błędne wywołanie aplikacji');
    }
    
    private function czyWpisaneWartosci()
    {
        return (isset($this->form->marka) && isset($this->form->model));
    }
    
    private function waliduj()
    {
        $walidacja = true;

        if ($this->form->marka == "")
        {
            App::getMessages()->addMessage(new Message('Nie podano marki pojazdu!', Message::ERROR));
            $walidacja = false;
        }
        if ($this->form->model == "")
        {
            App::getMessages()->addMessage(new Message('Nie podano modelu pojazdu!', Message::ERROR));
            $walidacja = false;
        }
        
        return $walidacja;
    }
    
    private function zapiszDaneNaBaze()
    {
        $idZalogowanegoUzytkownika = intval(SessionUtils::load("user",true)['ID_UZYTKOWNIKA']);
        try
        {
            App::getDB()->insert("POJAZDY", [
                "ID_UZYTKOWNIKA" => $idZalogowanegoUzytkownika,
                "MARKA_POJAZDU" => strval($this->form->marka),
                "MODEL_POJAZDU" => strval($this->form->model)
            ]);
        }
        catch (PDOException $e)
        {
            App::getMessages()->addMessage(new Message('Wystąpił błąd podczas dodawania pojazdu.', Message::ERROR));
        }
        finally
        {
            App::getMessages()->addMessage(new Message('Pomyślnie dodano pojazd.', Message::INFO));
        }
    }
    
    private function generujWidok()
    {
        App::getSmarty()->assign('page_title','Kalkulator spalania - dodawanie pojazdu');
        App::getSmarty()->assign('form',$this->form);     
        
        App::getSmarty()->display('pojazdy.tpl');
    }
}
