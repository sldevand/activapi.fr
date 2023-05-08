PRAGMA foreign_keys=off;

BEGIN TRANSACTION;

ALTER TABLE action RENAME TO _action_old;

CREATE TABLE IF NOT EXISTS action
(
  id           INTEGER PRIMARY KEY AUTOINCREMENT,
  nom VARCHAR(100) NOT NULL UNIQUE,
  actionneurId INTEGER NOT NULL,
  etat         INTEGER NOT NULL,
  timeout      REAL DEFAULT 0.0,
  CONSTRAINT fk_actionneurId
    FOREIGN KEY (actionneurId)
    REFERENCES actionneurs (id)
    ON DELETE CASCADE
);

INSERT INTO action SELECT * FROM _action_old;

DROP TABLE _action_old;

COMMIT;

BEGIN TRANSACTION;

ALTER TABLE sequence_action RENAME TO _sequence_action_old;

CREATE TABLE IF NOT EXISTS sequence_action
(
  id         INTEGER PRIMARY KEY AUTOINCREMENT,
  sequenceId INTEGER NOT NULL,
  actionId   INTEGER NOT NULL,
  CONSTRAINT fk_sequence_action_sequence FOREIGN KEY (sequenceId) REFERENCES sequence (id) ON DELETE CASCADE,
  CONSTRAINT fk_sequence_action_action FOREIGN KEY (actionId) REFERENCES action (id) ON DELETE CASCADE
);

INSERT INTO sequence_action SELECT * FROM _sequence_action_old;

DROP TABLE _sequence_action_old;

COMMIT;

BEGIN TRANSACTION;

ALTER TABLE scenario_sequence RENAME TO _scenario_sequence_old;

CREATE TABLE IF NOT EXISTS scenario_sequence
(
  id         INTEGER PRIMARY KEY AUTOINCREMENT,
  scenarioId INTEGER NOT NULL,
  sequenceId INTEGER NOT NULL,
  CONSTRAINT fk_scenario_sequence_scenario FOREIGN KEY (scenarioId) REFERENCES scenario (id) ON DELETE CASCADE,
  CONSTRAINT fk_scenario_sequence_sequence FOREIGN KEY (sequenceId) REFERENCES sequence (id) ON DELETE CASCADE
);

INSERT INTO scenario_sequence SELECT * FROM _scenario_sequence_old;

DROP TABLE _scenario_sequence_old;

COMMIT;

PRAGMA foreign_keys=on;