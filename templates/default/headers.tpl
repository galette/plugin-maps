{if $PAGENAME eq "maps.php" or $PAGENAME eq "mymap.php"}
        <link rel="stylesheet" type="text/css" href="{$galette_base_path}{$pluginc_dir}leaflet-0.5.1/leaflet.css"/>
        {* IE8 specific styles *}
        <!--[if lt IE 9]>
            <link rel="stylesheet" type="text/css" href="{$galette_base_path}{$pluginc_dir}leaflet-0.5.1/leaflet.ie.css"/>
        <![endif]-->
        <link rel="stylesheet" type="text/css" href="{$galette_base_path}{$maps_tpl_dir}galette_maps.css"/>
{/if}

