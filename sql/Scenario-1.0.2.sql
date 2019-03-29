DROP TABLE IF EXISTS action;

CREATE TABLE IF NOT EXISTS action
(
  id           INTEGER PRIMARY KEY AUTOINCREMENT,
  nom VARCHAR(100) NOT NULL UNIQUE,
  actionneurId INTEGER NOT NULL,
  etat         INTEGER NOT NULL,
  FOREIGN KEY (actionneurId) REFERENCES actionneurs (id)
);