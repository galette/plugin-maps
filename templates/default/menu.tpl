    {if $login->isLogged() and !$login->isSuperAdmin()}
        <h1 class="nojs">{_T string="Maps" domain="maps"}</h1>
        <ul>
            <li{if $cur_route eq 'maps_localize_member' or $cur_route eq 'maps_mymap'} class="selected"{/if}><a href="{path_for name="maps_mymap"}">{_T string="My localization" domain="maps"}</a></li>
        </ul>
    {/if}
