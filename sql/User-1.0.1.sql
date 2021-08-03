CREATE TABLE IF NOT EXISTS user
(
  id                INTEGER PRIMARY KEY AUTOINCREMENT,
  email             VARCHAR(100) NOT NULL UNIQUE,
  password          VARCHAR(100) NOT NULL,
  activationCode    VARCHAR(100),
  activated         NUMERIC NOT NULL,
  role              VARCHAR(100) NOT NULL,
  createdAt         NUMERIC,
  updatedAt         NUMERIC
);
