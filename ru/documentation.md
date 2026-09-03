---
title: Documentation
description: Member geolocation and public map
---

Этот плагин дает:

* возможность сохранять координаты для членов (широта и долгота)
* публичная карта показывает членов в реальном времени если они выбрали быть
  публично видимыми

## Установка

Прежде всего, загрузите плагин:

* [Get latest Maps
  plugin!](https://github.com/galette-plugins/plugin-maps/releases/latest)
* [Get Maps plugin nightly
  build!](https://github.com/galette-plugins/plugin-maps/releases/tag/nightly)

Распакуйте скачанный архив в папку Galette `plugins`. Например, под linux
(заменив `{url}` и `{version}` на правильные значения):

```bash
$ cd /var/www/html/galette/plugins
$ wget {url}
$ tar xjvf galette-plugin-maps-{version}.tar.bz2
```

## Установка БД

Для работы плагин требует несколько таблиц в БД. Смотри [Galette plugins
management
interface](https://doc.galette.eu/en/master/plugins/index.html#plugins-managment).

Все готово; Плагин Карты установлен :)

## Background map

{% include alert.html type="note" content="The provider setting appeared in
version 2.3.0." %}

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

{% include alert.html type="warning" content="Check the usage policy of the
provider you choose. Most of them are run by associations or by volunteers, and
they set conditions on the traffic they accept." %}

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

## Использование плагина

Когда плагин установлен, группа "Maps" добавляется в меню Galette когда член
входит в систему, В ней присутствует поле "Мое расположение". Эта страница
позволяет члену сохранять свою локацию.

Кнопка "Геолокализация" также добавляется при отображении члена, что позволяет
администрации устанавливать координаты членов.

Также в список публичных страниц добавлена запись "Карта" которая показывает
геолокализированых членов в реальном времени. Администрация и сотрудники будут
видеть всех членов, а члены и посетители только публичных.

Для начала все члены введут свои координаты. Дано несколько вариантов:

* if town has been set in member information, a list of possible places will be
  proposed (via [Nominatim online
  service](https://nominatim.openstreetmap.org)),
* additionally, a search zone (provided from
  [OpenStreetMap](https://nominatim.openstreetmap.org/)),
* and also a geolocalize button using browser capacities.

The search zone can be used when saving members location, and when displaying
the maps.

![The list of towns proposed for a member](images/towns_list.png)

A member can define its location (with the precision he wants) on the map
selecting one of the propositions:

![Selecting a location on the map](images/location_select.png)

Using the geolocalization button will define its position from the browser:

![The geolocalize button](images/geoloc.png)

Then, member location is displayed on map, and can be removed:

![The selected location, displayed on the map](images/location_selected.png)
