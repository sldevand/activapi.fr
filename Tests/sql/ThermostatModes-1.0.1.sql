DROP TABLE IF EXISTS 'thermostat_modes';

CREATE TABLE IF NOT EXISTS 'thermostat_modes' (
    'id' INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
    'nom' TEXT,
    'consigne' INTEGER,
    'delta' INTEGER
);
