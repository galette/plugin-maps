        <link rel="stylesheet" type="text/css" href="{$galette_base_path}{$maps_tpl_dir}galette_maps.css"/>
{if $PAGENAME eq "maps.php" or $PAGENAME eq "mymap.php" or $PAGENAME eq "adh_map.php"}
        <link rel="stylesheet" type="text/css" href="{$galette_base_path}{$pluginc_dir}leaflet-0.7.1/leaflet.css"/>
        {* IE8 specific styles *}
        <link rel="stylesheet" type="text/css" href="{$galette_base_path}{$pluginc_dir}leaflet-geosearch/css/l.geosearch.css"/>
        <link rel="stylesheet" type="text/css" href="{$galette_base_path}{$pluginc_dir}leaflet-fullscreencontrol/Control.FullScreen.css"/>
{/if}
{if $PAGENAME eq "mymap.php"}
        <link rel="stylesheet" type="text/css" href="{$galette_base_path}{$pluginc_dir}leaflet-locatecontrol/L.Control.Locate.css"/>
        <!--[if lt IE 9]>
            <link rel="stylesheet" type="text/css" href="{$galette_base_path}{$pluginc_dir}leaflet-locatecontrol/L.Control.Locate.ie.css"/>
        <![endif]-->
{/if}
