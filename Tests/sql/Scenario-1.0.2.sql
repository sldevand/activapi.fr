CREATE TABLE IF NOT EXISTS scenario_sequence
(
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  scenarioId INTEGER NOT NULL,
  sequenceId INTEGER NOT NULL,
  FOREIGN KEY (scenarioId) REFERENCES "scenario" (id),
  FOREIGN KEY (sequenceId) REFERENCES "sequence" (id)
);
