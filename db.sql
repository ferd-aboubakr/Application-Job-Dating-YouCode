USE job_dating_v2 ;
 
CREATE TABLE users (
id INT primary KEY  AUTO_INCREMENT ,
name VARCHAR(255) NOT NULL ,
email varchar(255) UNIQUE NOT NULL ,
password_hash varchar(255) ,
promotion VARCHAR(255),
specialisation VARCHAR(255),
ROLE ENUM('apprenant', 'admin') NOT NULL ,
created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE entreprises  (
id INT primary KEY AUTO_INCREMENT,
NAME VARCHAR(255) NOT NULL ,
secteur VARCHAR(255) NOT NULL ,
localisation VARCHAR(255) NOT NULL ,
email VARCHAR(255) NOT NULL UNIQUE,
telephone VARCHAR(20)

);

CREATE TABLE annonces (
 id INT primary KEY NOT NULL AUTO_INCREMENT ,
titre VARCHAR(255) ,
entreprise VARCHAR(255),
type_contrat VARCHAR(255) ,
localisation VARCHAR(255) ,
image VARCHAR(255) ,
description VARCHAR (255),
competences VARCHAR (255),
created_at DATETIME DEFAULT CURRENT_TIMESTAMP ,
deleted BOOLEAN DEFAULT FALSE ,
updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

entreprise_id INT NOT NULL ,

FOREIGN KEY (entreprise_id) REFERENCES entreprises(id) ON DELETE cascade


);