create table configuration
(
	id INTEGER not null primary key autoincrement,
	configKey TEXT UNIQUE,
	configValue TEXT
);
