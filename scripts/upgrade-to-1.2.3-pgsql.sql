ALTER TABLE galette_maps_coordinates DROP CONSTRAINT galette_maps_coordinates_id_adh_fkey;
ALTER TABLE galette_maps_coordinates ADD CONSTRAINT galette_maps_coordinates_id_adh_fkey FOREIGN KEY (id_adh) REFERENCES galette_adherents(id_adh) ON DELETE CASCADE ON UPDATE CASCADE;
