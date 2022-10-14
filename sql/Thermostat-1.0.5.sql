DROP TABLE IF EXISTS 'thermostat_planif';
CREATE TABLE IF NOT EXISTS 'thermostat_planif' (
    'id' INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
    'jour' INTEGER,
    'nomid' INTEGER,
    'timetable' TEXT
);

DELETE FROM thermostat_corresp;