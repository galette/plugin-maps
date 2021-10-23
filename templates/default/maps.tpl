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
        _member = [{$l.lat}, {$l.lng}, {$icon}, '<p><strong>{$l.name|escape}</strong>{if $l.nickname neq ''} {_T string="aka" domain="maps" escape="js"} <em>{$l.nickname|escape}</em>{/if}{if isset($l.company)}<br/>{$l.company|escape}{/if}</p>'];
        _markers.push(_member);
{/foreach}
        var _group = L.markerClusterGroup();
        for (var i = 0; i < _markers.length; i++) {
            var _a = _markers[i];
            var _title = _a[3];
            var _icon = _a[2];
            var _marker = L.marker(new L.LatLng(_a[0], _a[1]), { icon: _icon });
            _marker.bindPopup(_title);
            _group.addLayer(_marker);
        }
        map.addLayer(_group).fitBounds(
            _group.getBounds(), {
                padding: [50, 50],
                maxZoom: 12
            }
        );
    }
</script>
{/block}
