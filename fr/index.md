---
title: Galette Maps
description: Géolocalisation des membres et carte publique
---

Un plugin [Galette](https://galette.eu) pour géolocaliser les adhérents : il
stocke les coordonnées géographiques de chaque adhérent, et affiche ceux qui ont
accepté que leur profil soit publiquement visible sur une carte.

Le rende des cartes es assuré par [MapLibre GL](https://maplibre.org/) de
[OpenFreeMap](https://openfreemap.org/) tuiles vectorielles par défaut — pas de
clé API, pas d'enregistrement, et l'ensemble peut-être auto-hébergé. Un
administrateur peut sélectionner un autre fournisseur, ou entrer leur propre
adresse, depuis la configuration. La recherche est fournie via l'API
[Nominatim](https://nominatim.openstreetmap.org/).

* [documentation](documentation.html)
* [bugs and features](https://bugs.galette.eu/projects/galette-plugin-maps)
* [source code](https://github.com/galette-plugins/plugin-maps)
* mailing lists:
  [users](https://lists.mailman3.com/postorius/lists/galette-users.mailman3.com/),
  [developers](https://lists.mailman3.com/postorius/lists/galette-devel.mailman3.com/)

To use it you need a recent enough Galette — the required version is shown at
the top of this page — and the plugin itself, either by:

* downloading it with the buttons at the top of this page,
* or using the [source code from the
  repository](https://github.com/galette-plugins/plugin-maps), which requires
  some technical skills.

See the [documentation](documentation.html) for the installation steps.
