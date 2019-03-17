DROP TABLE IF EXISTS scenario;
DROP TABLE IF EXISTS scenario_corresp;

CREATE TABLE IF NOT EXISTS scenario 
(
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    nom VARCHAR(100) NOT NULL UNIQUE
);

CREATE TABLE IF NOT EXISTS sequence 
(
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    nom VARCHAR(100) NOT NULL UNIQUE,
    scenarioId INT NOT NULL,
    FOREIGN KEY (scenarioId) REFERENCES scenario (id) 
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

CREATE TABLE IF NOT EXISTS scenario_sequence
(
    scenarioId INTEGER NOT NULL,
    sequenceId INTEGER NOT NULL,
    FOREIGN KEY (scenarioId) REFERENCES "scenario" (id),
    FOREIGN KEY (sequenceId) REFERENCES "sequence" (id)
);