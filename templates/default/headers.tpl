        <link rel="stylesheet" type="text/css" href="{urlFor name="plugin_res" options=["plugin" => $module_id, "path" => "galette_maps.css"]}"/>
{if $cur_route|strpos:'maps_' === 0}
        <link rel="stylesheet" type="text/css" href="{urlFor name="plugin_res" options=["plugin" => $module_id, "path" => "leaflet-0.7.1/leaflet.css"]}"/>
        {* IE8 specific styles *}
        <link rel="stylesheet" type="text/css" href="{urlFor name="plugin_res" options=["plugin" => $module_id, "path" => "leaflet-geosearch/css/l.geosearch.css"]}"/>
        <link rel="stylesheet" type="text/css" href="{urlFor name="plugin_res" options=["plugin" => $module_id, "path" => "leaflet-fullscreencontrol/Control.FullScreen.css"]}"/>
{/if}
{if $cur_route eq 'maps_localize_member' or $cur_route eq 'maps_mymap'}
        <link rel="stylesheet" type="text/css" href="{urlFor name="plugin_res" options=["plugin" => $module_id, "path" => "leaflet-locatecontrol/L.Control.Locate.css"]}"/>
        <!--[if lt IE 9]>
            <link rel="stylesheet" type="text/css" href="{urlFor name="plugin_res" options=["plugin" => $module_id, "path" => "leaflet-locatecontrol/L.Control.Locate.ie.css"]}"/>
        <![endif]-->
{/if}
