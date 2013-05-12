<section>
    <div id="map"></div>
</section>
<script type="text/javascript">
    var _mapsBinded = function(map)
    {
{foreach $list as $l}

        L.marker([{$l.lat}, {$l.lng}]).addTo(map)
            .bindPopup('<p><strong>{$l.name|escape}</strong>{if $l.nickname neq ''} {_T string="aka" escape="js"} <em>{$l.nickname|escape}</em>{/if}</p>');
{/foreach}
    }
</script>
{include file='common_scripts.tpl'}

