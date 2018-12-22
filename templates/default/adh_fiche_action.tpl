    <li>
        <a
            href="{path_for name="maps_localize_member" data=["id" => $member->id]}"
            id="btn_plugins_maps"
            title="{_T string="Geolocalize %membername" domain="maps" pattern="/%membername/" replace=$member->sname}"
            class="button bigbutton tooltip"
        >
            <i class="fas fa-unlock fa-fw fa-2x"></i>
            {_T string="Geolocalize" domain="maps"}
        </a>
    </li>

