CREATE TABLE IF NOT EXISTS user
(
  id                INTEGER PRIMARY KEY AUTOINCREMENT,
  email             VARCHAR(100) NOT NULL UNIQUE,
  firstName         VARCHAR(100) NOT NULL,
  lastName          VARCHAR(100) NOT NULL,
  password          VARCHAR(100) NOT NULL,
  activationCode    VARCHAR(100),
  activated         NUMERIC NOT NULL,
  role              VARCHAR(100) NOT NULL,
  createdAt         NUMERIC,
  updatedAt         NUMERIC
);
