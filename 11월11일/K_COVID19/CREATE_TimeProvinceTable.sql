-- clean up
delete from TimeProvinceInfo;
drop table TimeProvinceInfo;

CREATE TABLE timeprovinceinfo (
	date date NOT NULL,
	province varchar(50),
	confirmed int(11),
	released int(11),
	deceased int(11),
	PRIMARY KEY (date, province)
	);