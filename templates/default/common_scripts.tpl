<script type="text/javascript" src="{$galette_base_path}{$pluginc_dir}leaflet-0.4.4/leaflet{if $GALETTE_MODE eq 'DEV'}-src{/if}.js"></script>
<script type="text/javascript">

    /**
        * Returns element height, including margins
        */
    function _eltRealSize(_elt) {
        var _s = 0;
        _s += _elt.outerHeight();
        _s += parseFloat(_elt.css('margin-top').replace('px', ''));
        _s += parseFloat(_elt.css('margin-bottom').replace('px', ''));
        return _s;
    }

    /**
        * Rewrite maps height
        */
    function _hresize() {
        var wheight = $(window).height();
        var _oSize = 0;

        //récuperation de la taille des autres lignes
{if $is_public}
        $('#map').parents('section').siblings(':not(script)').each(function(){
            _oSize += _eltRealSize($(this));
        });
{else}
        $('#map').parents('section').siblings(':not(script)').each(function(){
            _oSize += _eltRealSize($(this));
        });
        _oSize += _eltRealSize($('footer'));
        _oSize += parseFloat($('#content').css('margin-top').replace('px', ''));
{/if}

        //calcul et applicaiton de la nouvelle taille
        var newHeight = Math.floor(wheight - _oSize) + "px";
        $("#map").css("height", newHeight);
    }

    function _iLiveHere(_id){
        $('#' + _id).click(function(e){
            alert('One day, that will be stored in the database, stay in touch!!');
            return false;
        });
    }

    $(function(){
        _hresize();

        var _lat = {if $town}{$town['latitude']}{else}46.830133640447386{/if};
        var _lon = {if $town}{$town['longitude']}{else}2.4609375{/if};
        var map = L.map('map').setView([_lat, _lon], 6);

        L.tileLayer('http://{ldelim}s{rdelim}.tile.cloudmade.com/BC9A493B41014CAABB98F0471D759707/997/256/{ldelim}z{rdelim}/{ldelim}x{rdelim}/{ldelim}y{rdelim}.png', {
            maxZoom: 18,
            attribution: '{_T string="Map data ©"} <a href="http://openstreetmap.org">{_T string="OpenStreetMap contributors"}</a>, <a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, {_T string="Imagery ©"} <a href="http://cloudmade.com">CloudMade</a>'
        }).addTo(map);

        var popup = L.popup();

        function onMapClick(e) {
            var _clat = e.latlng.lat.toString();
            var _clng = e.latlng.lng.toString();
            var _id = 'coords_' + _clat.replace('.', '_') + _clng.replace('.', '_');
            popup
                .setLatLng(e.latlng)
                .setContent('<p>' + '{_T string="You clicked at %p"}'.replace('%p', '<em>' + _clat + '/' + _clng + '</em>') + '</p><p><a id="' + _id + '" href="#">{_T string="I live here!"}</a></p>')
                .openOn(map);
            _iLiveHere(_id);
        }

        map.on('click', onMapClick);
        try {
            _mapsBinded(map);
        } catch (err) {
            //fortunately, nothing to do here.
            //_mapsBinded function can be missing
        }
    });
</script>

