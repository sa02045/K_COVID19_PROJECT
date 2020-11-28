-- clean up
delete from timeageinfo;

drop table timeageinfo;

CREATE TABLE timeageinfo (
	date date NOT NULL,
	age varchar(10) NOT NULL,
	confirmed int,
	deceased int,
	PRIMARY KEY (date, age)
	);