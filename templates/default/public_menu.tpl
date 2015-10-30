{if !$public_page}

        <li{if $cur_route eq 'maps_map'} class="selected"{/if}><a href="{urlFor name="maps_map"}">{_T string="Maps"}</a></li>
        {else}
        <a id="pmaps" class="button{if $cur_route eq 'maps_map'} selected{/if}" href="{urlFor name="maps_map"}">{_T string="Maps"}</a>
{/if}
