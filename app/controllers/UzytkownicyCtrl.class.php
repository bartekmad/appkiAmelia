<?php namespace app\controllers;

use app\forms\UzytkownicyForm;
use core\App;
use core\SessionUtils;
use core\Message;
use core\ParamUtils;

class UzytkownicyCtrl {
    
    private $form;
    private $listaUzytkownikow;
    
    public function __construct()
    {
        $this->form = new UzytkownicyForm();
        $this->listaUzytkownikow;
    }
    
    public function action_panelUzytkownikow()
    {
        $this->generujWidok();
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
        $this->form->operacja = ParamUtils::getFromRequest('operacja',true,'Błędne wywołanie aplikacji');
    }
    
    private function pobierzListeUzytkownikow()
    {
        $this->listaUzytkownikow = App::getDB()->select("UZYTKOWNICY", ["ID_UZYTKOWNIKA","LOGIN"]);
    }
    
    public function action_dodajUzytkownika()
    {
        $this->pobierzParametryDodawania();
        if ($this->czyWpisaneWartosciDodawania())
        {
            if ($this->walidujDodawanieUzytkownika())
                $this->zapiszDaneNaBazeDodawanie();
        }
        $this->generujWidok();   
    }
    
    private function pobierzParametryDodawania()
    {
        $this->form->login = ParamUtils::getFromRequest('login',true,'Błędne wywołanie aplikacji');
        $this->form->haslo = ParamUtils::getFromRequest('haslo',true,'Błędne wywołanie aplikacji');
    }
    
    private function pobierzParametryEdycji()
    {
        $this->form->uzytkownik = ParamUtils::getFromRequest('uzytkownik',true,'Błędne wywołanie aplikacji');
        $this->form->haslo = ParamUtils::getFromRequest('haslo',true,'Błędne wywołanie aplikacji');
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
        $this->pobierzParametryEdycji();
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
        App::getSmarty()->assign('page_title','zarządzanie użytkownikami');
        App::getSmarty()->assign('form',$this->form);
        App::getSmarty()->assign('listaUzytkownikow',$this->listaUzytkownikow);
                
        App::getSmarty()->display('uzytkownicy.tpl');
    }
}
