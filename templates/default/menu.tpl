    {if $login->isLogged() and !$login->isSuperAdmin()}
        <h1 class="nojs">{_T string="Maps"}</h1>
        <ul>
            <li{if $cur_route eq 'maps_localize_member'} class="selected"{/if}><a href="{urlFor name="maps_mymap"}">{_T string="My localization"}</a></li>
        </ul>
    {/if}
