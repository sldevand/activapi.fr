DROP TABLE IF EXISTS 'thermostat_corresp';
DROP TABLE IF EXISTS 'thermostat_planif';

CREATE TABLE IF NOT EXISTS 'thermostat_corresp' (
    'id' INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
    'nom' TEXT
);

CREATE TABLE IF NOT EXISTS 'thermostat_planif'
(
    'id'            INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
    'jour'          INTEGER,
    'modeid'        INTEGER,
    'defaultModeid' INTEGER,
    'heure1Start'   DATETIME,
    'heure1Stop'    DATETIME,
    'heure2Start'   DATETIME,
    'heure2Stop'    DATETIME,
    'nomid'         INTEGER
);
