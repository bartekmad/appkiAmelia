<?php namespace app\controllers;

use app\forms\UzytkownicyForm;
use core\App;
use core\SessionUtils;
use core\Message;

class UzytkownicyCtrl {
    
    private $form;
    private $listaUzytkownikow;
    
    public function __construct()
    {
        $this->form = new UzytkownicyForm();
        $this->listaUzytkownikow;
    }
    
    public function action_zarzadzajUzytkownikami()
    {
        $this->ustawOperacje();
        if ($this->form->operacja == 1)
            $this->pobierzListeUzytkownikow();
        $this->generujWidok();
    }
    
    private function ustawOperacje()
    {
        $this->form->operacja = isset($_REQUEST['operacja']) ? $_REQUEST['operacja'] : null;
    }
    
    private function pobierzListeUzytkownikow()
    {
        $this->listaUzytkownikow = App::getDB()->select("UZYTKOWNICY", ["ID_UZYTKOWNIKA","LOGIN"]);
    }
    
    public function action_dodajUzytkownika()
    {
        $this->pobierzParametry();
        if ($this->czyWpisaneWartosciDodawania())
        {
            if ($this->walidujDodawanieUzytkownika())
                $this->zapiszDaneNaBazeDodawanie();
        }
        $this->generujWidok();   
    }
    
    private function pobierzParametry()
    {
        $this->form->login = isset($_REQUEST['login']) ? $_REQUEST['login'] : null;
        $this->form->uzytkownik = isset($_REQUEST['uzytkownik']) ? $_REQUEST['uzytkownik'] : null;
        $this->form->haslo = isset($_REQUEST['haslo']) ? $_REQUEST['haslo'] : null;
    }
    
    private function walidujDodawanieUzytkownika()
    {
        $walidacja = true;

        if ($this->form->login == "")
        {
            App::getMessages()->addMessage(new Message('Nie podano loginu użytkownika!', Message::ERROR));
            $walidacja = false;
        }
        if ($this->form->haslo == "")
        {
            App::getMessages()->addMessage(new Message('Nie podano hasła użytkownika!', Message::ERROR));
            $walidacja = false;
        }
        
        $wynik = App::getDB()->select("UZYTKOWNICY",[
            "LOGIN",
            ],
            [
            "LOGIN"=>$this->form->login
            ]
        );
        if (count($wynik) > 0)
        {
            foreach($wynik as $dana)
            {
                if ($this->form->login <= $dana["LOGIN"])
                {
                    App::getMessages()->addMessage(new Message('Użytkownik o podanym loginie istnieje w bazie!', Message::ERROR));
                    $walidacja = false;
                }
            }
        }
        
        return $walidacja;
    }
    
    private function zapiszDaneNaBazeDodawanie()
    {
        try
        {
            App::getDB()->insert("UZYTKOWNICY", [
                "LOGIN" => strval($this->form->login),
                "HASLO" => strval($this->form->haslo),
                "ROLA" => intval(1)
            ]);
        }
        catch (PDOException $e)
        {
            App::getMessages()->addMessage(new Message('Wystąpił błąd podczas dodawania użytkownika.', Message::ERROR));
        }
        finally
        {
            App::getMessages()->addMessage(new Message('Pomyślnie dodano użytkownika.', Message::INFO));
        }
    }
    
    private function czyWpisaneWartosciDodawania()
    {
        return (isset($this->form->login) && isset($this->form->haslo));
    }
    
    public function action_edytujUzytkownika()
    {
        $this->pobierzParametry();
        if ($this->czyWpisaneWartosciEdytowania())
        {
            if ($this->walidujEdytowanieUzytkownika())
                $this->zapiszDaneNaBazeEdycja();
        }
        $this->generujWidok();   
    }
    
    private function czyWpisaneWartosciEdytowania()
    {
        return (isset($this->form->uzytkownik) && isset($this->form->haslo));
    }
    
    private function walidujEdytowanieUzytkownika()
    {
        $walidacja = true;

        if ($this->form->uzytkownik == "")
        {
            App::getMessages()->addMessage(new Message('Nie wybrano użytkownika!', Message::ERROR));
            $walidacja = false;
        }
        if ($this->form->haslo == "")
        {
            App::getMessages()->addMessage(new Message('Nie podano hasla!', Message::ERROR));
            $walidacja = false;
        }
        
        return $walidacja;
    }
    
    private function zapiszDaneNaBazeEdycja()
    {
        try
        {
            App::getDB()->update("UZYTKOWNICY", [
                "HASLO" => strval($this->form->haslo)
                ],
                [
                "ID_UZYTKOWNIKA" => $this->form->uzytkownik
            ]);
        }
        catch (PDOException $e)
        {
            App::getMessages()->addMessage(new Message('Próba zmiany hasła nieudana.', Message::ERROR));
        }
        finally
        {
            App::getMessages()->addMessage(new Message('Pomyślnie zaktualizowano hasło.', Message::INFO));
        }
    }
    
    private function generujWidok()
    {
        App::getSmarty()->assign('page_title','Kalkulator spalania - zarządzanie użytkownikami');
        App::getSmarty()->assign('form',$this->form);
        App::getSmarty()->assign('listaUzytkownikow',$this->listaUzytkownikow);
                
        App::getSmarty()->display('uzytkownicy.tpl');
    }
}
