{extends file="main.tpl"}

{block name=content}
    
<form class="pure-form pure-form-stacked" action="{$conf->action_url}zarzadzajUzytkownikami" method="post">
    <label for="typ_operacji">Użytkownik: </label>    
    <select list="typ_operacji" id="typ_operacji" name="operacja" value="{$form->operacja}">
        <datalist id="typ_operacji">
            <option value="0">Dodaj nowego uzytkownika</option>
            <option value="1">Edytuj istniejącego uzytkownika</option>
        </datalist>
    </select>
    <br>
    <button type="submit" class="pure-button pure-button-primary">Wybierz operację</button>
</form> 

{/block}