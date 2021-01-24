{extends file="main.tpl"}

{block name=content}

<form class="pure-form pure-form-stacked" action="{$conf->action_url}wykonaj" method="post">
    <fieldset>
        <label for="id_kwotaTankowania">Kwota tankowania: </label>
        <input id="id_kwotaTankowania" type="text" name="kwotaTankowania" value="{$form->kwotaTankowania}">
        <label for="id_cenaZaLitr">Cena za litr paliwa: </label>
        <input id="id_cenaZaLitr" type="text" name="cenaZaLitr" value="{$form->cenaZaLitr}">
        <label for="id_stanPoczatkowy">Stan licznika początkowy: </label>
        <input id="id_stanPoczatkowy" type="number" name="stanPoczatkowy" value="{$form->stanPoczatkowy}">
        <label for="id_dataTankowania">Data tankowania: </label>
        <input id="id_dataTankowania" type="date" name="dataTankowania" value="{$form->dataTankowania}">
        <label for="id_pojazdu">Pojazd: </label>
        <select list="id_pojazdu" id="id_pojazdu" name="idPojazdu" value="{$form->idPojazdu}">
            <datalist id="id_pojazdu">
                {if $result}
                    {if (count($result) > 0)}
                        {foreach $result as $dana}
                            <option value="{$dana["ID_POJAZDU"]}">{$dana["MARKA_POJAZDU"]} {$dana["MODEL_POJAZDU"]}</option>
                        {/foreach}
                    {/if}
                {/if}
            </datalist>
        </select>
    </fieldset>
    <button type="submit" class="pure-button">Wpisz dane do bazy</button>
</form>

{/block}