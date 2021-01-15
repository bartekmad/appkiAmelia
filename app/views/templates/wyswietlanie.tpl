{extends file="main.tpl"}

{block name=content}

<form class="pure-form pure-form-stacked" action="{$conf->action_url}wyswietl" method="post">
    <label for="id_pojazdu">Pojazd: </label>    
    <select list="id_pojazdu" id="id_pojazdu" name="idPojazdu" value="{$form->idPojazdu}">
        <datalist id="id_pojazdu">
            {if $listaPojazdow}
                {if (count($listaPojazdow) > 0)}
                    {foreach $listaPojazdow as $dana}
                        <option value="{$dana["ID_POJAZDU"]}">{$dana["MARKA_POJAZDU"]} {$dana["MODEL_POJAZDU"]}</option>
                    {/foreach}
                {/if}
            {/if}
        </datalist>
    </select>
    <br>
    <button type="submit" class="pure-button pure-button-primary">Wyświetl dane dla podanego pojazdu</button>
</form>    

{if $result}
    {if (count($result) > 0)}
        <table class="tg">
            <thead>
                <tr>
                    <th class="tg-0lax">lp.</th>
                    <th class="tg-0lax">data</th>
                    <th class="tg-0lax">kwota</th>
                    <th class="tg-0lax">cena za litr</th>
                    <th class="tg-0lax">litry</th>
                    <th class="tg-0lax">stan licznika start</th>
                    <th class="tg-0lax">stan licznika stop</th>
                    <th class="tg-0lax">km przejechane</th>
                    <th class="tg-0lax">cena za 100km</th>
                    <th class="tg-0lax">spalanie na 100km</th>
                </tr>
            </thead>
            <tbody>
                {foreach $result as $dana}
                    <tr>
                        {$litry = $dana["KWOTA"] / $dana["CENA_LITR"]}
                        {$km = $dana["STAN_STOP"] - $dana["STAN_START"]}
                        {$cena_100 = $dana["KWOTA"] / $km * 100}
                        {$spalanie_100 = $litry / $km * 100}
                        {$czyPusta = $dana["STAN_STOP"] != null}
                        <td class="tg-0lax">{$dana["ID"]}</td>
                        <td class="tg-0lax">{$dana["DATA"]}</td>
                        <td class="tg-0lax">{$dana["KWOTA"]} zł</td>
                        <td class="tg-0lax">{$dana["CENA_LITR"]} zł</td>
                        <td class="tg-0lax">{round($litry, 2)}</td>
                        <td class="tg-0lax">{$dana["STAN_START"]}</td>
                        <td class="tg-0lax">{$dana["STAN_STOP"]}</td>
                        <td class="tg-0lax">{if $czyPusta}{$km}{/if}</td>
                        <td class="tg-0lax">{if $czyPusta}{round($cena_100, 2)} zł{/if}</td>
                        <td class="tg-0lax">{if $czyPusta}{round($spalanie_100, 2)} l{/if}</td>
                    </tr>
                {/foreach}
            </tbody>
        </table>
    {/if}
{/if}    
{/block}