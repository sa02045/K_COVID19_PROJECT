-- clean up
delete from TimeGenderInfo;
drop table TimeGenderInfo;

CREATE TABLE TimeGenderInfo (
	date date NOT NULL,
	sex varchar(10),
	confirmed int(11),
	deceased int(11),
    PRIMARY KEY(date,sex)
	);