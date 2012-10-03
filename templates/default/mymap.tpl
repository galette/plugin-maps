<section>
{*{foreach $results as $r}
    <p>{$r.full_name_nd_ro} - {$r.latitude} / {$r.longitude}</p>
{/foreach}*}
    <div id="map"></div>
</section>

{include file='common_scripts.tpl'}

