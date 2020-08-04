ALTER TABLE llx_clubsport ADD fk_product INT(11) NOT NULL DEFAULT 0;
ALTER TABLE llx_clubsport ADD CONSTRAINT fk_product FOREIGN KEY (fk_product) REFERENCES llx_product(rowid);
