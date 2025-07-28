CREATE TABLE etudiants (
     id INT NOT NULL AUTO_INCREMENT ,
     login VARCHAR(20) NOT NULL ,
      pass VARCHAR(256) NOT NULL , 
      nom VARCHAR(50) NOT NULL,
      note1 int(4) NOT NULL,
      note2 int(4) NOT NULL,
      moyenne float NOT NULL,
      longitude float NOT NULL,
      latitude float NOT NULL
       ) ENGINE = InnoDB;