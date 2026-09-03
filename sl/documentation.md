---
title: Dokumentacija
description: Member geolocation and public map
---

Ta vtičnik ponuja:

* možnost shranjevanja geografskih koordinat za člane (zemljepisna širina in
  dolžina),
* javni zemljevid, ki prikazuje posodobljene člane, ki so se odločili, da bodo
  javno vidni.

## Namestitev

Najprej prenesite vtičnik:

* [Get latest Maps
  plugin!](https://github.com/galette-plugins/plugin-maps/releases/latest)
* [Get Maps plugin nightly
  build!](https://github.com/galette-plugins/plugin-maps/releases/tag/nightly)

Razširite prenesen arhiv v imenik Galette `plugins`. Na primer v Linuxu
(zamenjajte `{url}` in `{version}` s pravilnimi vrednostmi):

```bash
$ cd /var/www/html/galette/plugins
$ wget {url}
$ tar xjvf galette-plugin-maps-{version}.tar.bz2
```

## Inicializacija baze podatkov

Za delovanje ta vtičnik potrebuje več tabel v bazi podatkov. Glejte [Vmesnik za
upravljanje vtičnikov
Galette](https://doc.galette.eu/en/master/plugins/index.html#plugins-managment).

In to je končano; vtičnik Zemljevidi je nameščen :)

## Background map

> Note
> {: .admonition-title}
>
> The provider setting appeared in version 2.3.0.
{: .admonition .admonition-note}

The provider is a setting from `Maps settings`, in the `Configuration` menu.

![The provider setting, in Maps settings](images/tiles_settings.png)

Several providers are proposed:

* **OpenFreeMap, light grey** — the default. Vector tiles, in a discreet grey
  that lets member markers stand out. No account, no API key, and the service
  can be [self-hosted](https://openfreemap.org).
* **OpenFreeMap, colours** — the same service, rendered in full colour.
* **OpenStreetMap** — the standard rendering, from the OpenStreetMap Foundation
  servers.
* **OpenStreetMap France** and **Humanitarian OSM Team** — hosted by the OSM-FR
  association; the second one gives more weight to roads and facilities.
* **OpenStreetMap Germany** — German rendering, favouring local names.
* **Esri, light grey** — a very light grey rendering, close to what the plugin
  displayed before.

### Your own values

> Warning
> {: .admonition-title}
>
> Check the usage policy of the provider you choose. Most of them are run by associations or by volunteers, and they set conditions on the traffic they accept.
{: .admonition .admonition-warning}

The last entry of the list, `Your own values`, replaces the proposed providers
with an address of your own — a provider that is not listed, or your own tile
server.

![The fields of the Your own values entry](images/tiles_custom.png)

* **Vector tiles** tells the plugin what it is being given: a MapLibre style
  when ticked, classic raster tiles when not.
* **Address** is the style address for vector tiles, and the tiles address for
  raster ones, such as `https://tile.openstreetmap.org/{z}/{x}/{y}.png`.
* **Attribution** is the credit the provider requires. HTML is allowed. It is
  not a formality: data licences make it mandatory.
* **Maximum zoom** is the deepest zoom level the provider serves. Going past it
  displays empty tiles.
* **Subdomains** lists the letters the `{s}` token of the address is replaced
  with, for instance `abc`. Raster tiles only.

## Plugin usage

Ko je vtičnik nameščen, se v meni Galette ob prijavi člana doda skupina
»Zemljevidi«, ki vsebuje vnos »Moja lokacija«. Ta stran članu omogoča
shranjevanje lokacije.

Pri prikazu člana je dodan tudi gumb »Geolokaliziraj«, ki skrbnikom omogoča
nastavitev koordinat člana.

Na seznamu javnih strani je dodan tudi vnos »Zemljevid«, ki prikazuje
geolokalizirane člane, ki so posodobljeni. Administratorji in člani osebja bodo
videli vse člane, medtem ko bodo preprosti člani in obiskovalci videli le
posodobljene javne.

Najprej bodo člani vnesli svoje koordinate lokacije. Na voljo je več možnosti:

* if town has been set in member information, a list of possible places will be
  proposed (via [Nominatim online
  service](https://nominatim.openstreetmap.org)),
* dodatno iskalno območje (zagotovljeno iz
  [OpenStreetMap](https://nominatim.openstreetmap.org/)),
* in tudi gumb za geolokacijo z uporabo zmogljivosti brskalnika.

Iskalno območje se lahko uporablja pri shranjevanju lokacije članov in pri
prikazu zemljevidov.

![The list of towns proposed for a member](images/towns_list.png)

Član lahko na zemljevidu določi svojo lokacijo (z želeno natančnostjo) z izbiro
enega od predlogov:

![Selecting a location on the map](images/location_select.png)

Z uporabo gumba za geolokalizacijo bo brskalnik določil njegov položaj:

![The geolocalize button](images/geoloc.png)

Nato se lokacija člana prikaže na zemljevidu in jo je mogoče odstraniti:

![The selected location, displayed on the map](images/location_selected.png)
