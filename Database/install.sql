-- Drop Database if exists zipdev_contacts;
-- Create Database zipdev_contacts;

Use zipdev_contacts;

Drop table if exists emails;
Drop table if exists phones;
Drop table if exists contacts;

Create Table contacts (
	contact_id int auto_increment,
    name varchar(64) not null,
    surname varchar(128) not null,
    image varchar(128) not null,
    primary key  (contact_id)
);

Create Table phones (
	phone_id int auto_increment,
    contact_id int(10) not null,
    phone varchar(10) not null,
    primary key  (phone_id),
    foreign key (contact_id) references contacts(contact_id)
		ON DELETE CASCADE
);

Create Table emails (
	email_id int auto_increment,
    contact_id int(10) not null,
    email varchar(128) not null,
    primary key  (email_id),
    foreign key (contact_id) references contacts(contact_id)
		ON DELETE CASCADE
);