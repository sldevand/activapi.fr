CREATE TABLE IF NOT EXISTS log
(
    id  INTEGER PRIMARY KEY AUTOINCREMENT,
    content TEXT NOT NULL,
    createdAt INTEGER NOT NULL
);

CREATE INDEX log_created_at ON log (createdAt);