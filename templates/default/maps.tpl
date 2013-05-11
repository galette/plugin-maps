<section>
    <div id="map"></div>
</section>
<script type="text/javascript">
    var _mapsBinded = function(map)
    {
{foreach $list as $l}

        L.marker([{$l.lat}, {$l.lng}]).addTo(map)
            .bindPopup('<p><strong>{$l.name}</strong>{if $l.nickname neq ''} {_T string="aka"} <em>{$l.nickname}</em>{/if}');
{/foreach}
    }
</script>
{include file='common_scripts.tpl'}

