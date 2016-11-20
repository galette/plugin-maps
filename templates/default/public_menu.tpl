{if !$public_page}

        <li{if $cur_route eq 'maps_map'} class="selected"{/if}><a href="{path_for name="maps_map"}">{_T string="Maps" domain="maps"}</a></li>
        {else}
        <a id="pmaps" class="button{if $cur_route eq 'maps_map'} selected{/if}" href="{path_for name="maps_map"}">{_T string="Maps" domain="maps"}</a>
{/if}
