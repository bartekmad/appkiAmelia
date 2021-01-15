{extends file="main.tpl"}

{block name=content}
    
<form class="pure-form pure-form-stacked" action="{$conf->action_url}zarzadzajPojazdami" method="post">
    <label for="typ_operacji">Pojazd: </label>    
    <select list="typ_operacji" id="typ_operacji" name="operacja" value="{$form->operacja}">
        <datalist id="typ_operacji">
            <option value="0">Dodaj nowy pojazd</option>
            <option value="1">Edytuj istniejący pojazd</option>
        </datalist>
    </select>
    <br>
    <button type="submit" class="pure-button pure-button-primary">Wybierz operację</button>
</form> 
    <br>
{if $form->operacja==0}
    Dodawanie nowego pojazdu<br>
    <form class="pure-form pure-form-stacked" action="{$conf->action_url}dodajPojazd" method="post">
        <fieldset>
            {if $rolaUzytkownika==0}
                <label for="id_uzytkownika">Użytkownik: </label>
                <select list="id_uzytkownika" id="id_uzytkownika" name="uzytkownik" value="{$form->uzytkownik}">
                    <datalist id="id_uzytkownika">
                        {if $listaUzytkownikow}
                            {if (count($listaUzytkownikow) > 0)}
                                {foreach $listaUzytkownikow as $dana}
                                    <option value="{$dana["ID_UZYTKOWNIKA"]}">{$dana["LOGIN"]}</option>
                                {/foreach}
                            {/if}
                        {/if}
                    </datalist>
                </select>
            {/if}
            <label for="marka_pojazdu">Marka Pojazdu: </label>
            <input id="marka_pojazdu" type="text" name="marka" value="{$form->marka}">
            <label for="model_pojazdu">Model pojazdu: </label>
            <input id="model_pojazdu" type="text" name="model" value="{$form->model}">
        </fieldset>
        <button type="submit" class="pure-button pure-button-primary">Wpisz dane do bazy</button>
    </form>
{/if}

{/block}