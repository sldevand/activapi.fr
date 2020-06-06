DROP TABLE IF EXISTS actionneurs;

create table actionneurs
(
	id INTEGER not null primary key autoincrement,
	nom TEXT,
	module TEXT,
	protocole TEXT,
	adresse TEXT,
	type TEXT,
	radioid INTEGER,
	etat TEXT,
	categorie TEXT
);
