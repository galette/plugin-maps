-- Schema
DROP TABLE IF EXISTS galette_maps_coordinates CASCADE;
CREATE TABLE galette_maps_coordinates (
    id_adh int(10) unsigned NOT NULL,
    latitude decimal(9,6) NOT NULL,
    longitude decimal(9,6) NOT NULL,
    PRIMARY KEY (id_adh),
    FOREIGN KEY (id_adh) REFERENCES galette_adherents (id_adh) ON DELETE CASCADE ON UPDATE CASCADE
);
