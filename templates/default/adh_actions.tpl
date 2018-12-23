            <a href="{path_for name="maps_localize_member" data=["id" => $member->id]}" class="tooltip">
                <i class="fas fa-map-pin"></i>
                <span class="sr-only">{_T string="Geolocalize %membername" domain="maps" pattern="/%membername/" replace=$member->sname}</span>
            </a>
