create table crontab
(
	id INTEGER not null primary key autoincrement,
	name TEXT,
	active INTEGER,
	expression TEXT,
	executor TEXT
);
