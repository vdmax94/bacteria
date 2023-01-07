CREATE TABLE familiae (
      id INT PRIMARY KEY AUTO_INCREMENT,
      divisio_id INT NOT NULL,
      class_id INT DEFAULT NULL,
      ordo_id INT DEFAULT NULL,
      name VARCHAR(150),

      FOREIGN KEY (divisio_id) REFERENCES divisio(id) ON DELETE NO ACTION,
      FOREIGN KEY (class_id) REFERENCES classes(id) ON DELETE SET NULL,
      FOREIGN KEY (ordo_id) REFERENCES ordines(id) ON DELETE SET NULL
);