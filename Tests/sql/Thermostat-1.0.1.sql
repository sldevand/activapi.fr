DROP TABLE IF EXISTS 'thermostat';

CREATE TABLE IF NOT EXISTS 'thermostat' (
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
    'releve'  DATETIME DEFAULT CURRENT_DATE,
    'pwr' INTEGER,
    'lastPwrOff' DATETIME,
    'mailSent' INTEGER DEFAULT 0
);