---
title: Documentation
description: Géolocalisation des membres et carte publique
---

Ce plugin fournit :

* la possibilité d'enregistrer les coordonnées géographiques des adhérents
  (latitude et longitude),
* une carte publique pour afficher les membres à jour qui ont choisi d'être
  affichés publiquement.

## Installation

Tout d'abord, téléchargez le plugin :

* [Get latest Maps
  plugin!](https://github.com/galette-plugins/plugin-maps/releases/latest)
* [Get Maps plugin nightly
  build!](https://github.com/galette-plugins/plugin-maps/releases/tag/nightly)

Extrayez l'archive téléchargée dans le dossier `plugins` de Galette. Par
exemple, sous linux (en remplaçant `{url}` et `{version}` par les valeurs
adéquates) :

```bash
$ cd /var/www/html/galette/plugins
$ wget {url}
$ tar xjvf galette-plugin-maps-{version}.tar.bz2
```

## Initialisation de la base de données

Pour fonctionner, ce plugin requiert des tables dans la base de données.
Référez-vous [à l'interface de gestion des plugins de
Galette](https://doc.galette.eu/en/master/plugins/index.html#plugins-managment).

Et c'est terminé, le plugin Maps est installé :)

## Fond de carte

{% include alert.html type="note" content="The provider setting appeared in
version 2.3.0." %}

Le fournisseur est un paramètre depuis `Configuration Maps`, dans le menu
`Configuration`.

![The provider setting, in Maps settings](images/tiles_settings.png)

Plusieurs fournisseurs sont proposés :

* **OpenFreeMap, gris clair** — par défaut. Les tuiles vectorisées, dans un gris
  discret qui permet aux marqueurs membres de se démarquer. Aucun compte, aucune
  clé API, et le service peut être [auto-hébergé](https://openfreemap.org).
* **OpenFreeMap, couleurs** — le même service, rendu en couleur.
* **OpenStreetMap** — le rendu standard, depuis serveurs de la fondation
  OpenStreetMap.
* **OpenStreetMap France** et **Équipe Humanitaire OSM** - hébergés par
  l'association OSM-FR ; le second donne plus de poids aux routes et aux
  installations.
* **OpenStreetMap Allemagne** — Le rendu allemand, en faveur des noms locaux.
* **Esri, gris clair** — un rendu gris très clair, proche de ce que le plugin
  affichait avant.

### Vos propres valeurs

{% include alert.html type="warning" content="Check the usage policy of the
provider you choose. Most of them are run by associations or by volunteers, and
they set conditions on the traffic they accept." %}

La dernière entrée de la liste, « Vos propres valeurs », remplace les
fournisseurs proposés par votre propre adresse — un fournisseur qui n'est pas
répertorié, ou votre propre serveur de tuiles.

![The fields of the Your own values entry](images/tiles_custom.png)

* **Tuiles vectorisées** configure le plugin pour ce qu'il va recevoir : un
  style MapLibre si coché, des tuiles classiques sinon.
* **Adresse** est l'adresse de style pour les tuiles vectorisées, et l'adresse
  des tuiles classiques, comme `https://tile.openstreetmap.org/{z}/{x}/{y}.png`.
* **Attribution** est le crédit demandé par le fournisseur. Le HTML est
  autorisé. Ce n'est pas une formalité : les licences le rendent obligatoire.
* **Zoom maximal** est le niveau de zoom le plus profond que le fournisseur
  serve. Après ça il affiche des tuiles vides.
* **Les sous-domaines** Lettres par lesquelles le jeton `{s}` dans l'adresse est
  remplacé, telles que abc. Tuiles classiques uniquement.

## Utilisation du plugin

Lorsque le plugin est installé, un groupe `Cartes` est ajouté au menu de Galette
lorsqu'un adhérent est connecté, qui contient l'entrée `Ma localisation`. Cette
page permet à l'adhérent d'enregistrer sa localisation.

Un bouton `Géolocaliser` est également ajouté à l'affichage d'une fiche
adhérent, qui permet aux administrateurs de définir les coordonnées de
l'adhérent.

De plus, une entrée `Carte` est ajoutée à la liste des pages publiques, qui
affiche la géolocalisation des adhérents à jour. Les administrateurs et membres
du bureau verront tous les adhérents, alors que les simples adhérents et les
visiteurs verront uniquement ceux à jour et publics.

Tout d'abord, les adhérents entreront leurs coordonnées géographiques. Plusieurs
options sont fournies :

* si la ville a été renseignée dans la fiche de l'adhérent, une liste des
  localisations possibles sera proposée (via [le service en ligne
  Nominatim](https://nominatim.openstreetmap.org)),
* en complément, une zone de recherche (fournie depuis
  [OpenStreetMap](https://nominatim.openstreetmap.org/)),
* et également une bouton de géolocalisation qui utilise les possibilités du
  navigateur.

La zone de recherche peut être utilisée lorsque vous renseignez la localisation
d'un adhérent, et aussi à l'affichage des cartes.

![The list of towns proposed for a member](images/towns_list.png)

Un adhérent peut définir a propre localisation (avec la précision qu'il
souhaite) sur la carte en sélectionnant une des propositions :

![Selecting a location on the map](images/location_select.png)

Utiliser le bouton géolocalisation déterminera sa position depuis son navigateur
:

![The geolocalize button](images/geoloc.png)

Alors, la localisation de l'adhérent est affichée sur la carte, et peut être
supprimée :

![The selected location, displayed on the map](images/location_selected.png)
