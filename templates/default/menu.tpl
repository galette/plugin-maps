    {if $login->isLogged() and !$login->isSuperAdmin()}
        <h1 class="nojs">{_T string="Maps"}</h1>
        <ul>
            <li{if $PAGENAME eq "mymap.php"} class="selected"{/if}><a href="{$galette_base_path}{$galette_galette_maps_path}mymap.php">{_T string="My localization"}</a></li>
        </ul>
    {/if}
