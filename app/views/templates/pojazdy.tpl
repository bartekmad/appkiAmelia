{extends file="main.tpl"}

{block name=content}
    
<form class="pure-form pure-form-stacked" action="{$conf->action_url}dodajPojazd" method="post">
    <fieldset>
        <label for="marka_pojazdu">Marka Pojazdu: </label>
        <input id="marka_pojazdu" type="text" name="marka" value="{$form->marka}">
        <label for="model_pojazdu">Model pojazdu: </label>
        <input id="model_pojazdu" type="text" name="model" value="{$form->model}">
    </fieldset>
    <button type="submit" class="pure-button pure-button-primary">Dodaj pojazd</button>
</form>

{/block}