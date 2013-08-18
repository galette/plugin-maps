<script type="text/javascript" src="{$galette_base_path}{$pluginc_dir}leaflet-0.6.4/leaflet{if $GALETTE_MODE eq 'DEV'}-src{/if}.js"></script>
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
        $('#map').parents('section').siblings(':not(script)').each(function(){
            _oSize += _eltRealSize($(this));
        });
        _oSize += _eltRealSize($('footer'));
        var _c = $('#content');
        _oSize += parseFloat(_c.css('margin-top').replace('px', ''));
        _oSize += parseFloat(_c.css('padding-top').replace('px', ''));
        _oSize += parseFloat(_c.css('padding-bottom').replace('px', ''));

        //calcul et applicaiton de la nouvelle taille
        var newHeight = Math.floor(wheight - _oSize) + "px";
        $("#map").css("height", newHeight);
    }

    function _iLiveHere(_id){
        $('#' + _id).click(function(e, f,g){
            var _a = $(this);
            var _latlng = _a.data('latlng');
            $.ajax({
                url: 'ajax_ilivehere.php',
                type: 'POST',
                data: {
                    latitude: _latlng.lat,
                    longitude: _latlng.lng
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
        _hresize();

        var _lat = {if isset($town)}{$town['latitude']}{else}46.830133640447386{/if};
        var _lon = {if isset($town)}{$town['longitude']}{else}2.4609375{/if};
        var map = L.map('map').setView([_lat, _lon], {if isset($town)}12{else}6{/if});

        L.tileLayer('http://{ldelim}s{rdelim}.tile.cloudmade.com/BC9A493B41014CAABB98F0471D759707/997/256/{ldelim}z{rdelim}/{ldelim}x{rdelim}/{ldelim}y{rdelim}.png', {
            maxZoom: 18,
            attribution: '{_T string="Map data ©" escape="js"} <a href="http://openstreetmap.org">{_T string="OpenStreetMap contributors" escape="js"}</a>, <a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, {_T string="Imagery ©" escape="js"} <a href="http://cloudmade.com">CloudMade</a>'
        }).addTo(map);

        try {
            _mapsBinded(map);
        } catch (err) {
            //fortunately, nothing to do here.
            //_mapsBinded function can be missing
        }
    });
</script>

