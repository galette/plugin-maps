    {if $login->isLogged() and !$login->isSuperAdmin()}
        <h1 class="nojs">{_T string="Maps"}</h1>
        <ul>
            <li{if $cur_route eq 'maps_localize_member' or $cur_route eq 'maps_mymap'} class="selected"{/if}><a href="{path_for name="maps_mymap"}">{_T string="My localization"}</a></li>
        </ul>
    {/if}
