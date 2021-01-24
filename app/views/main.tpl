<!doctype html>
<html lang="pl">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="{$page_description|default:'Opis domyślny'}">
	<title>{$page_title|default:"Tytuł domyślny"}</title>
	<link rel="stylesheet" href="https://unpkg.com/purecss@0.6.2/build/pure-min.css" integrity="sha384-UQiGfs9ICog+LwheBSRCt1o5cbyKIHbwjWscjemyBMT9YCUMZffs6UqUTd0hObXD" crossorigin="anonymous">
	<link rel="stylesheet" href="{$conf->app_url}/css/style.css">	
</head>
<body>

<div class="header">
	<h1>{$page_title|default:"Tytuł domyślny"}</h1>
</div>

{if count($conf->roles)>0}
    <div class="panel">
        <ul>         
            {if isset($conf->roles['0'])}
                <li>
                    <a href="{$conf->action_root}panelUzytkownikow">Zarządzaj użytkownikami</a>
                </li>
            {/if}
            <li>
                <a href="{$conf->action_root}zarzadzajPojazdami">Zarządzaj pojazdami</a>
            </li>
            <li>
               <a href="{$conf->action_root}wprowadz">Wprowadz dane tankowania</a>
            </li>
            <li>
                <a href="{$conf->action_root}wyswietlanie">Wyswietl dane tankowan</a>
            </li>
            <li style="float:right">
                <a href="{$conf->action_root}logout">Wyloguj</a>
            </li>
        </ul>
    </div>
{/if}

{if $msgs->isError() or $msgs->isInfo()}
<div class="messages">
    {block name=messages}

    {if $msgs->isError()}
    <div class="err">
        <ul>
            {foreach $msgs->getMessages() as $msg}
            {strip}
                <li>{$msg->text}</li>
            {/strip}
            {/foreach}
        </ul>
    </div>
    {/if}

    {if $msgs->isInfo()}
    <div class="inf">
        <ul>
            {foreach $msgs->getMessages() as $msg}
            {strip}
                <li>{$msg->text}</li>
            {/strip}
            {/foreach}
        </ul>
    </div>
    {/if}

    {/block}
</div>
{/if}

<div class="content">
{block name=content}
{/block}
</div><!-- content -->

<div class="footer">
    <p>
    {block name=footer}
    {/block}
    </p>
    <p>
        Widok oparty na stylach <a href="http://purecss.io/" target="_blank">Pure CSS Yahoo!</a>. (autor przykładu: Przemysław Kudłacik)
    </p>
</div>

</body>
</html>