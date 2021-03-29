{extends file="main.tpl"}

{block name=content}

<form class="pure-form pure-form-stacked" action="{$conf->action_url}wyswietl" method="post">
    <label for="id_pojazdu">Pojazd: </label>    
    <select list="id_pojazdu" id="id_pojazdu" name="idPojazdu" value="{$form->idPojazdu}">
        <datalist id="id_pojazdu">
            {if $listaPojazdow}
                {if (count($listaPojazdow) > 0)}
                    {foreach $listaPojazdow as $dana}
                        <option value="{$dana["ID_POJAZDU"]}"{if $dana["ID_POJAZDU"]==$idPojazdu}selected{/if}>{$dana["MARKA_POJAZDU"]} {$dana["MODEL_POJAZDU"]}{if $rolaUzytkownika == 0} ({$dana["LOGIN"]}){/if}</option>
                    {/foreach}
                {/if}
            {/if}
        </datalist>
    </select>
    <br>
    <button type="submit" class="pure-button">Wyświetl dane dla podanego pojazdu</button>
</form>
<br>        

{if $stronaAktualna}
strona {$stronaAktualna} z {$stronIlosc}
{/if}
{if $stronaAktualna < $stronIlosc}
    <form class="pure-form pure-form-stacked" action="{$conf->action_url}nastepnaStrona" method="post">
        <button type="submit" class="pure-button">Nastepna strona</button>
    </form>
{/if}    
    
<br>
{if $result}
    {if (count($result) > 0)}
        <table class="pure-table pure-table-horizontal">
            <thead>
                <tr>
                    <th>lp.</th>
                    <th>data</th>
                    <th>kwota</th>
                    <th>cena za litr</th>
                    <th>litry</th>
                    <th>stan licznika start</th>
                    <th>stan licznika stop</th>
                    <th>km przejechane</th>
                    <th>cena za 100km</th>
                    <th>spalanie na 100km</th>
                </tr>
            </thead>
            <tbody>
                {$ID = count($result)}
                {$ID = $ID * $stronaAktualna}
                {foreach $result as $dana}
                    <tr>
                        {$litry = $dana["KWOTA"] / $dana["CENA_LITR"]}
                        {$km = $dana["STAN_STOP"] - $dana["STAN_START"]}
                        {$cena_100 = $dana["KWOTA"] / $km * 100}
                        {$spalanie_100 = $litry / $km * 100}
                        {$czyPusta = $dana["STAN_STOP"] != null}
                        <td>{$ID}</td>
                        <td>{$dana["DATA"]}</td>
                        <td>{$dana["KWOTA"]} zł</td>
                        <td>{$dana["CENA_LITR"]} zł</td>
                        <td>{round($litry, 2)}</td>
                        <td>{$dana["STAN_START"]}</td>
                        <td>{$dana["STAN_STOP"]}</td>
                        <td>{if $czyPusta}{$km}{/if}</td>
                        <td>{if $czyPusta}{round($cena_100, 2)} zł{/if}</td>
                        <td>{if $czyPusta}{round($spalanie_100, 2)} l{/if}</td>
                        {$ID = $ID - 1}
                    </tr>
                {/foreach}
            </tbody>
        </table>
    {/if}
{/if}    
{/block}