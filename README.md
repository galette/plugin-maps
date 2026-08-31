# Galette Maps plugin

[![GitHub license](https://img.shields.io/github/license/galette/plugin-maps)](https://github.com/galette/plugin-maps/blob/master/COPYING)

### English

A [Galette](https://galette.eu) plugin to geolocalize members.

Map display uses [Leaflet project](https://leafletjs.com/) while search is provided by [Nominatim](https://nominatim.openstreetmap.org/) API.
Background tiles are the [OpenFreeMap](https://openfreemap.org/) vector tiles, rendered with [MapLibre GL](https://maplibre.org/): no API key, no registration, and the whole thing can be self-hosted.
That is only the default: an administrator picks another provider, or enters their own address, from Configuration.

* website: https://galette.eu - https://doc.galette.eu/en/master/plugins/maps.html
* bugs and features: https://bugs.galette.eu/projects/galette-plugin-maps
* mailing lists:
  * users: https://lists.mailman3.com/postorius/lists/galette-users.mailman3.com/
  * developers: https://lists.mailman3.com/postorius/lists/galette-devel.mailman3.com/
* documentation: https://doc.galette.eu/en/master/plugins/maps.html

To use Galette Maps plugin, you'll need a reliable Galette version, and of course the plugin itself by either:

* download latest stable version available from [Galette Maps plugin page](https://doc.galette.eu/en/master/plugins/maps.html)
* use [Galette Maps plugin source code from repository](https://doc.galette.eu/en/develop/development/git.html) (make sure you install third party dependencies), this solution requires some technical skills

### Français

Un plugin [Galette](https://galette.eu) pour gérer paiments de cotisation et de dons via Maps.

Les cartes sont affichées par le biais du [projet Leaflet](https://leafletjs.com/) tandis que la recherche est assurée par l'API [Nominatim](https://nominatim.openstreetmap.org/).
Le fond de carte provient des tuiles vectorielles [OpenFreeMap](https://openfreemap.org/), rendues par [MapLibre GL](https://maplibre.org/) : sans clé d'API, sans inscription, et auto-hébergeable.
Ce n'est que le défaut : un administrateur choisit un autre fournisseur, ou saisit sa propre adresse, depuis la Configuration.

* site web : https://galette.eu - https://doc.galette.eu/fr/master/plugins/maps.html
* bogues et fonctionnalités : https://bugs.galette.eu/projects/galette-plugin-maps
* liste de diffusion :
  * utilisateurs : https://lists.mailman3.com/postorius/lists/galette-users.mailman3.com/
  * développeurs : https://lists.mailman3.com/postorius/lists/galette-devel.mailman3.com/
* documentation : https://doc.galette.eu/fr/master/plugins/maps.html

Pour utiliser le plugin Maps pour Galette, vous aurez besoin d'une version adéquate de Galette, ainsi que du plugin lui-même :

* télécharger la dernière version stable depuis la [page du plugin Maps pour Galette](https://doc.galette.eu/en/master/plugins/maps.html)
* utiliser [le code source du plugin Maps pour Galette depuis le dépôt](https://doc.galette.eu/en/develop/development/git.html) (assurez-vous d'installer les bibliothèques tierces), cette solution requiert quelques compétences techniques
