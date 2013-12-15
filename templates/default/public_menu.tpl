{if !$public_page}
        <li{if $PAGENAME eq "maps.php"} class="selected"{/if}><a href="{$galette_base_path}{$galette_galette_maps_path}maps.php">{_T string="Maps"}</a></li>
        {else}
        <a id="pmaps" class="button{if $PAGENAME eq "maps.php"} selected{/if}" href="{$galette_base_path}{$galette_galette_maps_path}maps.php">{_T string="Maps"}</a>
        {/if}
