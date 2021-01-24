<?php

use core\App;
use core\Utils;

App::getRouter()->setDefaultRoute('wprowadzanie');
App::getRouter()->setLoginRoute('login');
Utils::addRoute('wprowadzanie', 'CalcCtrl', ['0','1']);
Utils::addRoute('wykonaj', 'CalcCtrl', ['0','1']);
Utils::addRoute('wyswietlanie', 'WyswietlanieCtrl', ['0','1']);
Utils::addRoute('wyswietl', 'WyswietlanieCtrl', ['0','1']);
Utils::addRoute('login', 'LoginCtrl');
Utils::addRoute('logout', 'LoginCtrl');
Utils::addRoute('zarzadzajUzytkownikami', 'UzytkownicyCtrl', ['0']);
Utils::addRoute('dodajUzytkownika', 'UzytkownicyCtrl', ['0']);
Utils::addRoute('edytujUzytkownika', 'UzytkownicyCtrl', ['0']);
Utils::addRoute('panelUzytkownikow', 'UzytkownicyCtrl', ['0']);
Utils::addRoute('zarzadzajPojazdami', 'PojazdyCtrl', ['0','1']);
Utils::addRoute('dodajPojazd', 'PojazdyCtrl', ['0','1']);