ALTER TABLE galette_maps_coordinates DROP FOREIGN KEY galette_maps_coordinates_ibfk_1;
ALTER TABLE galette_maps_coordinates ADD CONSTRAINT galette_maps_coordinates_ibfk_1 FOREIGN KEY (id_adh) REFERENCES galette_adherents(id_adh) ON DELETE CASCADE ON UPDATE CASCADE;
