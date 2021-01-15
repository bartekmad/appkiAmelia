<?php namespace app\controllers;

use app\forms\PojazdyForm;
use core\App;
use core\SessionUtils;
use core\Message;

class PojazdyCtrl
{
    private $form;
    private $rolaUzytkownika;
    private $listaUzytkownikow;
    
    public function __construct()
    {
        $this->form = new PojazdyForm();
        $this->rolaUzytkownika;
        $this->listaUzytkownikow;
    }
    
    public function action_zarzadzajPojazdami()
    {
        $this->ustawRoleUzytkownikaIPobierzListe();
        $this->ustawOperacje();
        $this->generujWidok();
    }
            
    private function ustawRoleUzytkownikaIPobierzListe()
    {
        $this->rolaUzytkownika = intval(SessionUtils::load("user",true)['ROLA']);
        $this->listaUzytkownikow = App::getDB()->select("UZYTKOWNICY", ["ID_UZYTKOWNIKA","LOGIN"]);
    }
            
    private function ustawOperacje()
    {
        $this->form->operacja = isset($_REQUEST['operacja']) ? $_REQUEST['operacja'] : null;
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
        $this->form->uzytkownik = isset($_REQUEST['uzytkownik']) ? $_REQUEST['uzytkownik'] : null;
        $this->form->marka = isset($_REQUEST['marka']) ? $_REQUEST['marka'] : null;
        $this->form->model = isset($_REQUEST['model']) ? $_REQUEST['model'] : null;
    }
    
    private function czyWpisaneWartosci()
    {
        return (isset($this->form->uzytkownik) && isset($this->form->marka) && isset($this->form->model));
    }
    
    private function waliduj()
    {
        $walidacja = true;

        if ($this->form->uzytkownik == "")
        {
            App::getMessages()->addMessage(new Message('Nie udalo się pobrać danych użytkownika!', Message::ERROR));
            $walidacja = false;
        }
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
        try
        {
            App::getDB()->insert("POJAZDY", [
                "ID_UZYTKOWNIKA" => intval($this->form->uzytkownik),
                "MARKA_POJAZDU" => strval($this->form->marka),
                "MODEL_POJAZDU" => strval($this->form->model)
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
    
    private function generujWidok()
    {
        App::getSmarty()->assign('page_title','Kalkulator spalania - zarządzanie pojazdami');
        App::getSmarty()->assign('form',$this->form);
        App::getSmarty()->assign('rolaUzytkownika',$this->rolaUzytkownika);
        App::getSmarty()->assign('listaUzytkownikow',$this->listaUzytkownikow);        
        
        App::getSmarty()->display('pojazdy.tpl');
    }
}
