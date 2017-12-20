{extends file="page.tpl"}
{block name="content"}
<section>
    <div id="map"></div>
</section>
{include file='file:[maps]common_html.tpl'}
{/block}

{block name="javascripts"}
{include file='file:[maps]common_scripts.tpl'}
<script type="text/javascript">
    var _mapsBinded = function(map)
    {
        var _markers = [];
{foreach $list as $l}
    {if isset($l.company) and $l.company neq ''}
        {assign var=icon value='galetteProIcon'}
    {else}
        {assign var=icon value='galetteIcon'}
    {/if}
        _marker = L.marker([{$l.lat}, {$l.lng}], {ldelim}icon: {$icon}{rdelim}).bindPopup('<p><strong>{$l.name|escape}</strong>{if $l.nickname neq ''} {_T string="aka" domain="maps" escape="js"} <em>{$l.nickname|escape}</em>{/if}{if isset($l.company)}<br/>{$l.company|escape}{/if}</p>');
        _markers.push(_marker);
{/foreach}
        var _group = L.featureGroup(_markers).addTo(map);
        map.fitBounds(
            _group.getBounds(), {
                padding: [50, 50],
                maxZoom: 12
            }
        );
    }
</script>
{/block}
