create database dbms;
use dbms;

create table avatar (
	avatarID int unsigned not null auto_increment primary key,
	image varchar(50) not null
	
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

create table icon (
	iconID int unsigned not null auto_increment primary key,
	description varchar(25) not null
	
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

create table groups (
	groupID int unsigned not null auto_increment primary key,
	usergroup varchar(20) not null
	
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

create table users (
	username varchar(20) not null primary key,
	password char(40) not null,
	firstname varchar(50) not null,
	lastname varchar(50) not null,
	website varchar(200) not null,
	email varchar(100) not null,
	dbase varchar(50) not null,
	avatarID int unsigned not null,
	groupID int unsigned not null,
	
	foreign key (avatarID) references avatar(avatarID),
	foreign key (groupID) references groups(groupID)
) ENGINE=InnoDB DEFAULT CHARSET=utf8; 

create table links (
	linkID int unsigned not null auto_increment primary key,
	username varchar(20) not null,
	linkname varchar(50) not null,
	dashboard char(1),
	characters int unsigned,
	iconID int unsigned,
	table1 varchar(50),
	table2 varchar(50),
	pkey varchar(50),
	fkey varchar(50),
	column1 varchar(50),
	column2 varchar(50),
	column3 varchar(50),
	column4 varchar(50),
	column5 varchar(50),
	column6 varchar(50),
	column7 varchar(50),
	column8 varchar(50),
	column9 varchar(50),
	column10 varchar(50),
	limitqry int unsigned,
	orderqry varchar(10),	
	
	foreign key (username) references users(username),
	foreign key (iconID) references icon(iconID)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

create table notifications (
	notificationID int unsigned not null auto_increment primary key,
	title varchar(50),
	description varchar(500),
	startdate date,
	enddate date
	
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

create table notifyuser (
	nuID int unsigned not null auto_increment primary key,
	notificationID int unsigned not null,
	username varchar(20),
	groupID int unsigned,
	dismiss char(1),
	
	foreign key (notificationID) references notifications(notificationID),
	foreign key (username) references users(username),
	foreign key (groupID) references groups(groupID)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO avatar (avatarID, image) VALUES
(20001, 'assets/images/avatar1.png'),
(20002, 'assets/images/avatar2.png'),
(20003, 'assets/images/avatar3.png'),
(20004, 'assets/images/avatar4.png'),
(20005, 'assets/images/avatar5.png'),
(20006, 'assets/images/avatar6.png');

INSERT INTO icon (iconID, description) VALUES
(10001, 'fa fa-th'),
(10002, 'fa fa-th-large'),
(10003, 'fa fa-th-list'),
(10004, 'fa fa-link'),
(10005, 'fa fa-list'),
(10006, 'fa fa-list-alt'),
(10007, 'fa fa-list-ol'),
(10008, 'fa fa-list-ul'),
(10009, 'fa fa-table');

INSERT INTO groups (groupID, usergroup) VALUES
(101, 'admin'),
(102, 'client'),
(103, 'standard'),
(104, 'guest');

INSERT INTO users (username, password, firstname, lastname, website, email, dbase, avatarID, groupID) VALUES
('admin', '5baa61e4c9b93f3f0682250b6cf8331b7ee68fd8', 'admin', 'user', 'domain.com', 'admin@domain.com', 'dbms', 20001, 101);
