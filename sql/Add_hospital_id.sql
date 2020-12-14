-- drop hospital_id attribute in patientinfo table
alter table patientinfo drop hospital_id;

-- add hospital_id attribute in patientinfo table
alter table patientinfo add hospital_id int;