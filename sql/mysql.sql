-- Schema
DROP TABLE IF EXISTS galette_maps_towns;
CREATE TABLE galette_maps_towns (
    rc int(1),
    ufi int(38) NOT NULL,
    id int(38) NOT NULL,
    latitude real NOT NULL,
    longitude real NOT NULL,
    dms_latitude real NOT NULL,
    dms_longitude real NOT NULL,
    mgrs varchar(15),
    jog varchar(7),
    classification char(1),
    dsg varchar(6),
    pc smallint,
    cc1 varchar(255),
    adm1 varchar(2),
    pop varchar(38),
    elev varchar(126),
    cc2 varchar(255),
    nt varchar(2),
    lc varchar(3),
    short_form varchar(128),
    generic varchar(128),
    sort_name_ro varchar(255) NOT NULL,
    full_name_ro varchar(255) NOT NULL,
    full_name_nd_ro varchar(255) NOT NULL,
    sort_name_rg varchar(255) NOT NULL,
    full_name_rg varchar(255) NOT NULL,
    full_name_nd_rg varchar(255) NOT NULL,
    note text,
    modif_date date,
    PRIMARY KEY (id)
);

DROP TABLE IF EXISTS galette_maps_coordinates CASCADE;
CREATE TABLE galette_maps_coordinates (
    id_adh int(10) unsigned NOT NULL,
    latitude real NOT NULL,
    longitude real NOT NULL,
    PRIMARY KEY (id_adh),
    FOREIGN KEY (id_adh) REFERENCES galette_adherents (id_adh)
);
