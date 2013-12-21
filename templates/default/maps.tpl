<section>
    <div id="map"></div>
</section>
{include file='common_scripts.tpl'}
<script type="text/javascript">
    var _mapsBinded = function(map)
    {
{foreach $list as $l}
    {if isset($l.company) and $l.company neq ''}
        {assign var=icon value='galetteProIcon'}
    {else}
        {assign var=icon value='galetteIcon'}
    {/if}
        L.marker([{$l.lat}, {$l.lng}], {ldelim}icon: {$icon}{rdelim}).addTo(map)
            .bindPopup('<p><strong>{$l.name|escape}</strong>{if $l.nickname neq ''} {_T string="aka" escape="js"} <em>{$l.nickname|escape}</em>{/if}{if isset($l.company)}<br/>{$l.company|escape}{/if}</p>');
{/foreach}
    }
</script>

