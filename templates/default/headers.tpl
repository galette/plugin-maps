{if $PAGENAME eq "maps.php" or $PAGENAME eq "mymap.php"}
        <link rel="stylesheet" type="text/css" href="{$galette_base_path}{$pluginc_dir}leaflet-0.6.4/leaflet.css"/>
        {* IE8 specific styles *}
        <!--[if lt IE 9]>
            <link rel="stylesheet" type="text/css" href="{$galette_base_path}{$pluginc_dir}leaflet-0.6.4/leaflet.ie.css"/>
        <![endif]-->
        <link rel="stylesheet" type="text/css" href="{$galette_base_path}{$maps_tpl_dir}galette_maps.css"/>
{/if}
{if $PAGENAME eq "mymap.php"}
        <link rel="stylesheet" type="text/css" href="{$galette_base_path}{$pluginc_dir}leaflet-locatecontrol/L.Control.Locate.css"/>
        <link rel="stylesheet" type="text/css" href="{$galette_base_path}{$pluginc_dir}leaflet-geosearch/css/l.geosearch.css"/>
        <!--[if lt IE 9]>
            <link rel="stylesheet" type="text/css" href="{$galette_base_path}{$pluginc_dir}leaflet-locatecontrol/L.Control.Locate.ie.css"/>
        <![endif]-->
{/if}
