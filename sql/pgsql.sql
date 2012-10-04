-- Schema
DROP TABLE IF EXISTS galette_maps_towns CASCADE;
CREATE TABLE galette_maps_towns (
    rc integer,
    ufi integer NOT NULL,
    id integer NOT NULL,
    latitude real NOT NULL,
    longitude real NOT NULL,
    dms_latitude real NOT NULL,
    dms_longitude real NOT NULL,
    mgrs character varying(15),
    jog character varying(7),
    classification character varying(1),
    dsg character varying(6),
    pc smallint,
    cc1 character varying(255),
    adm1 character varying(2),
    pop character varying(38),
    elev character varying(126),
    cc2 character varying(255),
    nt character varying(2),
    lc character varying(3),
    short_form character varying(128),
    generic character varying(128),
    sort_name_ro character varying(255) NOT NULL,
    full_name_ro character varying(255) NOT NULL,
    full_name_nd_ro character varying(255) NOT NULL,
    sort_name_rg character varying(255) NOT NULL,
    full_name_rg character varying(255) NOT NULL,
    full_name_nd_rg character varying(255) NOT NULL,
    note text,
    modif_date date,
    PRIMARY KEY (id)
);

DROP TABLE IF EXISTS galette_maps_coordinates CASCADE;
CREATE TABLE galette_maps_coordinates (
    id_adh integer REFERENCES galette_adherents (id_adh) ON DELETE RESTRICT ON UPDATE CASCADE,
    latitude real NOT NULL,
    longitude real NOT NULL,
    PRIMARY KEY (id_adh)
);
