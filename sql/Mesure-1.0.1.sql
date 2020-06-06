CREATE TABLE mesures (
    id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
    id_sensor TEXT,
    temperature DECIMAL (4, 2),
    hygrometrie DECIMAL (4, 2),
    horodatage DATETIME
);

CREATE INDEX "" ON mesures (
    horodatage
);
