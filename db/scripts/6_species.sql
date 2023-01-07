CREATE TABLE species (
     id INT PRIMARY KEY AUTO_INCREMENT,
     divisio_id INT NOT NULL,
     class_id INT DEFAULT NULL,
     ordo_id INT DEFAULT NULL,
     familia_id INT DEFAULT NULL,
     genus_id INT NOT NULL,
     name VARCHAR(150) NOT NULL,
     scientist VARCHAR(255) NOT NULL,
     year SMALLINT NOT NULL,

     FOREIGN KEY (divisio_id) REFERENCES divisio(id) ON DELETE NO ACTION,
     FOREIGN KEY (class_id) REFERENCES classes(id) ON DELETE SET NULL,
     FOREIGN KEY (ordo_id) REFERENCES ordines(id) ON DELETE SET NULL,
     FOREIGN KEY (familia_id) REFERENCES familiae(id) ON DELETE SET NULL,
     FOREIGN KEY (genus_id) REFERENCES genera(id) ON DELETE NO ACTION
);