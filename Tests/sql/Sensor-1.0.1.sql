DROP TABLE IF EXISTS 'sensors';

CREATE TABLE IF NOT EXISTS 'sensors' (
    'id' INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
    'radioid' TEXT,
    'releve' DATETIME,
    'actif' BOOLEAN NOT NULL DEFAULT 'true',
    'valeur1' REAL,
    'valeur2' REAL,
    'nom' TEXT,
    'categorie' TEXT,
    'radioaddress' TEXT
);
