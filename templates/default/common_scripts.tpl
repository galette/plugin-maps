<div id="legende" title="{_T string="Legend"}">
    <h1>{_T string="Legend"}</h1>
    <table>
        <tr>
            <th>
                <img src="{$galette_base_path}{$pluginc_dir}leaflet-0.7.1/images/marker-galette.png" alt="{_T string="Member"}"/>
            </th>
            <td>
                {_T string="Member"}
            </td>
        </tr>
        <tr>
            <th>
                <img src="{$galette_base_path}{$pluginc_dir}leaflet-0.7.1/images/marker-galette-pro.png" alt="{_T string="Member (company)"}"/>
            </th>
            <td>
                {_T string="Member (company)"}
            </td>
        </tr>
        <tr>
            <th>
                <img src="{$galette_base_path}{$pluginc_dir}leaflet-0.7.1/images/marker-icon.png" alt="{_T string="Search result"}"/>
            </th>
            <td>
                {_T string="Search result"}
            </td>
        </tr>
    </table>
</div>
<script type="text/javascript" src="{$galette_base_path}{$pluginc_dir}leaflet-0.7.1/leaflet{if $GALETTE_MODE eq 'DEV'}-src{/if}.js"></script>
<script type="text/javascript" src="{$galette_base_path}{$pluginc_dir}leaflet-geosearch/js/l.control.geosearch.js"></script>
<script type="text/javascript" src="{$galette_base_path}{$pluginc_dir}leaflet-geosearch/js/l.geosearch.provider.openstreetmap.js"></script>
{if $PAGENAME eq "mymap.php"}
<script type="text/javascript" src="{$galette_base_path}{$pluginc_dir}leaflet-locatecontrol/L.Control.Locate.js"></script>
{/if}
<script type="text/javascript" src="{$galette_base_path}{$pluginc_dir}leaflet-legendcontrol/L.Control.Legend.js"></script>
<script type="text/javascript" src="{$galette_base_path}{$pluginc_dir}leaflet-fullscreencontrol/Control.FullScreen.js"></script>
<script type="text/javascript">

    /**
     * Returns element height, including margins
     */
    function _eltRealSize(_elt) {
        var _s = 0;
        _s += _elt.outerHeight();
        _s += parseFloat(_elt.css('margin-top').replace('px', ''));
        _s += parseFloat(_elt.css('margin-bottom').replace('px', ''));
        _s += parseFloat(_elt.css('padding-top').replace('px', ''));
        _s += parseFloat(_elt.css('padding-bottom').replace('px', ''));
        return _s;
    }

    /**
     * Rewrite maps height
     */
    function _hresize() {
        var wheight = $(window).height();
        var _oSize = 0;

        //récuperation de la taille des autres lignes
        $('#map').parents('section').siblings(':not(script)').each(function(){
            var _this = $(this);
            if ( !_this.hasClass('ui-dialog') ) {
                _oSize += _eltRealSize($(this));
            }
        });
        if ( $('#content').length > 0 ) {
            _oSize += _eltRealSize($('footer'));
            _oSize += parseFloat($('#content').css('padding-top').replace('px', ''));
            _oSize += parseFloat($('#content').css('padding-bottom').replace('px', ''));
        }

        //calcul et application de la nouvelle taille
        var newHeight = Math.floor(wheight - _oSize);
        var minHeight = 300;
        if ( newHeight < minHeight ) {
            newHeight = minHeight;
        }
        $("#map").css("height", newHeight + "px");
    }

    /**
     * Galette specific marker icon
     */
    var galetteIcon = L.icon({
        iconUrl: '{$galette_base_path}{$pluginc_dir}leaflet-0.7.1/images/marker-galette.png',
        iconSize: [25, 41],
        iconAnchor: [12, 41],
        popupAnchor: [1, -34],
        shadowSize: [41, 41]
    });
    var galetteProIcon = L.icon({
        iconUrl: '{$galette_base_path}{$pluginc_dir}leaflet-0.7.1/images/marker-galette-pro.png',
        iconSize: [25, 41],
        iconAnchor: [12, 41],
        popupAnchor: [1, -34],
        shadowSize: [41, 41]
    });

    function _iLiveHere(_id){
        $('#' + _id).click(function(e, f,g){
            var _a = $(this);
            var _latlng = _a.data('latlng');
            $.ajax({
                url: 'ajax_ilivehere.php',
                type: 'POST',
                data: {
                    latitude: _latlng.lat,
                    longitude: _latlng.lng{if isset($adhmap)},
                    id_adh: {$member->id}{/if}
                },
                {include file="../../../../templates/default/js_loader.tpl"},
                success: function(res){
                    //not very pretty... but that works for the moment :)
                    alert(res);
                    window.location.reload();
                },
                error: function(){
                    alert("{_T string="An error occured during 'I live here' process :(" escape="js"}")
                }
            });
            return false;
        });
    }

    $(function(){
        var _legendhtml = $('#legende').clone();
        _legendhtml.find('h1').remove()
        $('#legende').remove();

        _hresize();

        var _lat = {if isset($town)}{$town['latitude']}{else}46.830133640447386{/if};
        var _lon = {if isset($town)}{$town['longitude']}{else}2.4609375{/if};
        var map = L.map(
            'map', {
                fullscreenControl: true,
                fullscreenControlOptions: {
                    title: "{_T string="Display map in full screen"}",
                    forceSeparateButton:true
                }
            }
        ).setView([_lat, _lon], {if isset($town)}12{else}6{/if});

        new L.Control.GeoSearch({
            provider: new L.GeoSearch.Provider.OpenStreetMap(),
{if $PAGENAME eq "mymap.php" and !isset($town)}
            searchLabel: '{_T string="Search your town..." escape="js"}',
{else}
            searchLabel: '{_T string="Search a town..." escape="js"}',
{/if}
            notFoundMessage: '{_T string="Sorry, that town could not be found." escape="js"}',
            zoomLevel: 13
        }).addTo(map);

        L.control.legend({
            strings: {
                title: '{_T string="Show legend"}'
            }
        }).addTo(map);

        _legend = L.control({
            position: 'bottomright'
        });
        _legend.onAdd = function (map) {
            var div = L.DomUtil.create('div', 'info legend');
            div.innerHTML = _legendhtml.html();
            return div;
        }
        _legend.addTo(map);

{if $PAGENAME eq "mymap.php"}
        L.control.locate({
            strings: {
                title: '{_T string="Show me where I am" escape="js"}',
                popup: 'SELECTPOPUP',
                outsideMapBoundsMsg: '{_T string="You seem located outside the boundaries of the map" escape="js"}'
            }
        }).addTo(map);
{/if}

        L.tileLayer('http://{ldelim}s{rdelim}.mqcdn.com/tiles/1.0.0/map/{ldelim}z{rdelim}/{ldelim}x{rdelim}/{ldelim}y{rdelim}.jpg', {
            subdomains: ['otile1', 'otile2', 'otile3', 'otile4'],
            maxZoom: 18,
            attribution: '{_T string="Map data ©" escape="js"} <a href="http://openstreetmap.org">{_T string="OpenStreetMap contributors" escape="js"}</a>, {_T string="Imagery ©" escape="js"} <a href="http://www.mapquest.com">MapQuest</a> <img src="http://developer.mapquest.com/content/osm/mq_logo.png">'
        }).addTo(map);

        try {
            _mapsBinded(map);
        } catch (err) {
            //fortunately, nothing to do here.
            //_mapsBinded function can be missing
        }
    });
</script>

