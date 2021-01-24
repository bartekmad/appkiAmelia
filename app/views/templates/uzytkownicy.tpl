{extends file="main.tpl"}

{block name=content}
    
<form class="pure-form pure-form-stacked" action="{$conf->action_url}zarzadzajUzytkownikami" method="post">
    <label for="typ_operacji">Użytkownik: </label>    
    <select list="typ_operacji" id="typ_operacji" name="operacja" value="{$form->operacja}">
        <datalist id="typ_operacji">
            <option value="0" {if $form->operacja==0}selected{/if}>Dodaj nowego uzytkownika</option>
            <option value="1" {if $form->operacja==1}selected{/if}>Edytuj istniejącego uzytkownika</option>
        </datalist>
    </select>
    <br>
    <button type="submit" class="pure-button">Wybierz operację</button>
</form> 
        
{if $form->operacja==0}
    <form class="pure-form pure-form-stacked" action="{$conf->action_url}dodajUzytkownika" method="post">
        <fieldset>
            <label for="login_uzytkownika">Login użytkownika: </label>
            <input id="login_uzytkownika" type="text" name="login" value="{$form->login}">
            <label for="haslo_uzytkownika">Hasło użytkownika: </label>
            <input id="haslo_uzytkownika" type="text" name="haslo" value="{$form->haslo}">
        </fieldset>
        <button type="submit" class="pure-button">Dodaj użytkownika</button>
    </form>
{/if}

{if $form->operacja==1}
    <form class="pure-form pure-form-stacked" action="{$conf->action_url}edytujUzytkownika" method="post">
        <fieldset>
            <label for="id_uzytkownika">Użytkownik: </label>    
            <select list="id_uzytkownika" id="id_uzytkownika" name="uzytkownik" value="{$form->uzytkownik}">
                <datalist id="id_pojazdu">
                    {if $listaUzytkownikow}
                        {if (count($listaUzytkownikow) > 0)}
                            {foreach $listaUzytkownikow as $dana}
                                <option value="{$dana["ID_UZYTKOWNIKA"]}">{$dana["LOGIN"]}</option>
                            {/foreach}
                        {/if}
                    {/if}
                </datalist>
            </select>
            <label for="haslo_uzytkownika">Hasło użytkownika: </label>
            <input id="haslo_uzytkownika" type="text" name="haslo" value="{$form->haslo}">
        </fieldset>
        <button type="submit" class="pure-button">Zmień hasło użytkownika</button>
    </form>
{/if}

{/block}