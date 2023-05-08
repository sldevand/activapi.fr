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

PRAGMA foreign_keys=on;