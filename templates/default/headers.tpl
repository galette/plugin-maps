        <link rel="stylesheet" type="text/css" href="{path_for name="plugin_res" data=["plugin" => $module_id, "path" => "galette_maps.css"]}"/>
        <link rel="stylesheet" type="text/css" href="{path_for name="plugin_res" data=["plugin" => $module_id, "path" => "js/maps.bundle.css"]}"/>
{if $cur_route|strpos:'maps_' === 0}
        <link rel="stylesheet" type="text/css" href="{path_for name="plugin_res" data=["plugin" => $module_id, "path" => "js/leaflet.css"]}"/>
        <link rel="stylesheet" type="text/css" href="{path_for name="plugin_res" data=["plugin" => $module_id, "path" => "leaflet-control-osm-geocoder/Control.OSMGeocoder.css"]}"/>
        <link rel="stylesheet" type="text/css" href="{path_for name="plugin_res" data=["plugin" => $module_id, "path" => "js/Control.FullScreen.css"]}"/>
{/if}
{if $cur_route eq 'maps_localize_member' or $cur_route eq 'maps_mymap'}
        <link rel="stylesheet" type="text/css" href="{path_for name="plugin_res" data=["plugin" => $module_id, "path" => "leaflet-locatecontrol/L.Control.Locate.min.css"]}"/>
{/if}
