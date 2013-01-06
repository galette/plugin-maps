{*{if $login->isLogged()}*}
        <h1 class="nojs">{_T string="Maps"}</h1>
        <ul>
    {if $login->isLogged() and !$login->isSuperAdmin()}
            <li{if $PAGENAME eq "mymap.php"} class="selected"{/if}><a href="{$galette_base_path}{$galette_galette_maps_path}mymap.php">{_T string="My localization"}</a></li>
    {/if}
            <li{if $PAGENAME eq "maps.php"} class="selected"{/if}><a href="{$galette_base_path}{$galette_galette_maps_path}maps.php">{_T string="Maps"}</a></li>
    {*if $login->isAdmin() or $login->isStaff()}
            <li{if $PAGENAME eq "maps_preferences.php"} class="selected"{/if}><a href="{$galette_base_path}{$galette_galette_maps_path}maps_preferences.php">{_T string="Maps Preferences"}</a></li>
    {/if*}
        </ul>
{*{/if}*}
