--  clean up hospitalinfo table
delete from hospitalinfo;

drop table hospitalinfo;

CREATE TABLE hospitalinfo (
	hospital_id int NOT NULL,
	hospital_name varchar(70),
	hospital_province varchar(50),
	hospital_city varchar(50),
	hospital_latitude float,
	hospital_longitude float,
	capacity int,
	now int,
	PRIMARY KEY (hospital_id)
	);