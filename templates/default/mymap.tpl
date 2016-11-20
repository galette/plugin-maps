{extends file="page.tpl"}
{block name="content"}
<section>
    <div id="map"></div>
{if isset($towns) and $towns|@count > 0}
    <aside id="possible_towns" title="{if isset($mymap)}{_T string="Choose your location" domain="maps"}{_T string="Choose %member location" domain="maps" pattern="/%member/" replace=$member->sname}{/if}">
        <p>{_T string="Select your town." domain="maps"}<br/>{_T string="In the database, town is set to: '%town'" domain="maps" pattern="/%town/" replace=$member->town}</p>
        <ul>
    {foreach $towns as $t}
            <li><strong>{$t.full_name}</strong> (<em><span class="lat">{$t.latitude}</span>/<span class="lon">{$t.longitude}</span></em>)</li>
    {/foreach}
        </ul>
    </aside>
{/if}
</section>
{include file='file:[maps]common_html.tpl'}
{/block}

{block name="javascripts"}
{include file='file:[maps]common_scripts.tpl'}
{if $cur_route neq 'maps_map'}
<script type="text/javascript">
    var _mapsBinded = function(map)
    {

        function onMapClick(e) {
            var popup = L.popup();
            popup
                .setLatLng(e.latlng)
                .setContent('SELECTPOPUP')
                .openOn(map);
        }

        map.on('click', onMapClick);

        //bind "I live here" event on popupopen
        map.on('popupopen', function(e){
            var _popup = e.popup;
            var _container = $(_popup._container);
            if ( _container.find('#removecoords').length > 0 ) {
                _bind_removecoords();
            } else if ( _container.find('.leaflet-popup-content').html() == 'SELECTPOPUP' ) {
                var _clat = _popup._latlng.lat.toString();
                var _clng = _popup._latlng.lng.toString();
                var _id = 'coords_' + _clat.replace('.', '_') + _clng.replace('.', '_');

                _popup.setContent('<p>' + '{_T string="You clicked at %p" domain="maps" escape="js"}'.replace('%p', '<em>' + _clat + '/' + _clng + '</em>') + '</p><p><a id="' + _id + '" href="#">{if isset($mymap)}{_T string="I live here!" domain="maps" escape="js"}{else}{_T string="Member lives here!" domain="maps" escape="js"}{/if}</a></p>');
            }

            var _links = $(_container).find('a');
            _a = $(_links[1]);
            _a.data('latlng', e.popup._latlng);
            _iLiveHere(_a.attr('id'));
        });


{if isset($town)}
    {* Town is known, just display *}
        var _lat = {$town['latitude']};
        var _lon = {$town['longitude']};

        var _bind_removecoords = function(){
            console.log('called');
            $('#removecoords').click(function(){
                var _d = $('<div title="{if isset($mymap)}{_T string="Remove my coordinates" domain="maps" escape="js"}{else}{_T string="Remove member coordinates" domain="maps" escape="js"}{/if}">{_T string="Are you sure you want to remove coordinates from the database?" domain="maps" escape="js"}</div>');
                _d.dialog({
                    modal: true,
                    width: '40%',
                    buttons: {
                        '{_T string="Remove"}': function(){
                            $.ajax({
                                url: '{if isset($mymap)}{path_for name="maps_ilivehere"}{else}{path_for name="maps_ilivehere" data=["id" => $member->id]}{/if}',
                                type: 'POST',
                                data: {
                                    remove: true
                                },
                                {include file="js_loader.tpl"},
                                success: function(res){
                                    _d.dialog('close');
                                    alert(res.message);
                                    if (res.res == true ) {
                                        //not very pretty... but that works for the moment :)
                                        window.location.reload();
                                    }
                                },
                                error: function(){
                                    alert("{_T string="An error occured removing coordinates :(" domain="maps" escape="js"}")
                                }
                            });
                        },
                        '{_T string="Cancel" escape="js"}': function(){
                            $(this).dialog('close');
                        }
                    }
                });
            });
        };

        L.marker([_lat, _lon], {ldelim}icon: galetteIcon{rdelim}).addTo(map)
            .bindPopup('<strong>{$member->sfullname|escape}</strong><br/>{if isset($mymap)}{_T string="I live here!" domain="maps" escape="js"}{else}{_T string="Member lives here!" domain="maps" escape="js"}{/if}<br/><span id="removecoords">{_T string="Remove" escape="js"}</span>').openPopup();
{elseif isset($towns)}
    {* Town is not known. Show possibilities *}
        var _towns = $('#possible_towns');
        _towns.dialog({
            width: '400px',
            position: ['right', 'middle']
        });

        _towns.find('li').click(function(e){
            var _elt = $(this);
            var _name = _elt.find('strong')[0].innerHTML;
            var _slat = _elt.find('.lat')[0].innerHTML;
            var _slon = _elt.find('.lon')[0].innerHTML;

            map.setView([parseFloat(_slat), parseFloat(_slon)], 13);
            var _id = 'coords_' + _slat.replace('.', '_') + _slon.replace('.', '_');
            L.marker([_slat, _slon]).addTo(map)
                .bindPopup('<p><strong>' + _name  + '</strong><br/><em>' + _slat + '/' + _slon + '</em></p><p><a id="' + _id + '" href="#">{if isset($mymap)}{_T string="I live here!" domain="maps" escape="js"}{else}{_T string="Member lives here!" domain="maps" escape="js"}{/if}</a></p>').openPopup();
        });
{/if}
    }
</script>
{/if}
{/block}
