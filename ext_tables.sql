#
# Table structure for table 'tx_chhaendlersuche_plz'
#
CREATE TABLE tx_chhaendlersuche_plz (
	uid int(11) unsigned DEFAULT '0' NOT NULL auto_increment,
	pid int(11) unsigned DEFAULT '0' NOT NULL,
	tstamp int(11) unsigned DEFAULT '0' NOT NULL,
	crdate int(11) unsigned DEFAULT '0' NOT NULL,
	cruser_id int(11) unsigned DEFAULT '0' NOT NULL,
	plz varchar(10) DEFAULT '' NOT NULL,
	ortsname char(100) DEFAULT '' NOT NULL,
	laengengrad char(30) DEFAULT '' NOT NULL,
	breitengrad char(30) DEFAULT '' NOT NULL,
	PRIMARY KEY (uid),
	KEY plz (pid),
	KEY ortsname (pid),
	KEY laengengrad (pid),
	KEY breitengrad (pid),
);