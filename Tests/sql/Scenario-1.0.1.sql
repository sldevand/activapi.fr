DROP TABLE IF EXISTS scenario;
DROP TABLE IF EXISTS scenario_corresp;
DROP TABLE IF EXISTS sequence;
DROP TABLE IF EXISTS action;
DROP TABLE IF EXISTS sequence_action;

CREATE TABLE IF NOT EXISTS scenario 
(
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    nom VARCHAR(100) NOT NULL UNIQUE
);

CREATE TABLE IF NOT EXISTS sequence 
(
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    nom VARCHAR(100) NOT NULL UNIQUE
);

CREATE TABLE IF NOT EXISTS action
(
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    actionneurId INTEGER NOT NULL,
    etat INTEGER NOT NULL,
    FOREIGN KEY (actionneurId) REFERENCES actionneurs (id) 
);

CREATE TABLE IF NOT EXISTS sequence_action
(
    sequenceId INTEGER NOT NULL,
    actionId INTEGER NOT NULL,
    FOREIGN KEY (sequenceId) REFERENCES "sequence" (id),
    FOREIGN KEY (actionId) REFERENCES "action" (id)
);