CREATE TABLE 'thermostat' (
    'id' INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
    'nom' TEXT,
    'modeid' INTEGER,
    'sensorid' INTEGER,
    'planning' INTEGER,
    'manuel' INTEGER,
    'consigne' INTEGER,
    'delta' INTEGER,
    'interne' INTEGER,
    'etat' INTEGER,
    'releve'  DATETIME DEFAULT CURRENT_DATE
);


CREATE TABLE 'thermostat_corresp' (
    'id' INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
    'nom' TEXT
);

CREATE TABLE 'thermostat_log' (
    'id' INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
    'etat' INTEGER DEFAULT 0,
    'horodatage' DATETIME,
    'consigne' REAL,
    'delta' REAL
);

CREATE INDEX thermostat_log_horodatage ON thermostat_log (horodatage);

CREATE TABLE 'thermostat_modes' (
    'id' INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
    'nom' TEXT,
    'consigne' INTEGER,
    'delta' INTEGER
);

CREATE TABLE 'thermostat_planif' (
    'id' INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
    'jour' INTEGER,
    'modeid' INTEGER,
    'defaultModeid' INTEGER,
    'heure1Start' DATETIME,
    'heure1Stop' DATETIME,
    'heure2Start' DATETIME,
    'heure2Stop' DATETIME,
    'nomid' INTEGER
);
