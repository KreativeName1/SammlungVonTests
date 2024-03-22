create database ajaxTest;
use ajaxTest;

create table notes (
  note_id int(11) NOT NULL auto_increment,
  text text NOT NULL,
  date datetime not null default current_timestamp(),
  title varchar(255) NOT NULL,
  color char(6) NOT NULL,
  description text NULL,
  PRIMARY KEY  (note_id)
);