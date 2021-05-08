create schema if not exists vacc_db collate utf8_general_ci;

create table if not exists priority_group
(
	priority_level int auto_increment
		primary key,
	eligible_date date not null,
	priority_desc varchar(255) null
);

create table if not exists role
(
	role_id int auto_increment
		primary key,
	role_name varchar(20) not null,
	role_desc varchar(255) null
);

create table if not exists time_slot
(
	w_id int not null,
	t_id int not null,
	weekday varchar(10) null,
	time_block time null,
	primary key (w_id, t_id)
);

create table if not exists user
(
	user_id int auto_increment
		primary key,
	user_name varchar(255) not null,
	user_password varchar(20) not null,
	role_id int not null,
	constraint user_user_name_uindex
		unique (user_name),
	constraint user_role_role_id_fk
		foreign key (role_id) references role (role_id)
			on update cascade on delete cascade
);

create table if not exists patient
(
	patient_id int not null
		primary key,
	patient_name varchar(50) not null,
	ssn varchar(10) not null,
	birth date not null,
	gender varchar(10) not null,
	patient_address varchar(255) not null,
	patient_phone varchar(10) null,
	patient_email varchar(255) not null,
	priority_level int null,
	max_distance int not null,
	patient_longitude double(9,6) not null,
	patient_latitude double(9,6) not null,
	constraint patient_priority_group_priority_level_fk
		foreign key (priority_level) references priority_group (priority_level)
			on update cascade on delete cascade,
	constraint patient_user_user_id_fk
		foreign key (patient_id) references user (user_id)
			on update cascade on delete cascade,
	constraint patient_user_user_name_fk
		foreign key (patient_email) references user (user_name)
			on update cascade on delete cascade
);

create table if not exists patient_preferred_time
(
	ppt_id int auto_increment
		primary key,
	patient_id int not null,
	w_id int not null,
	t_id int not null,
	constraint patient_preferred_time_patient_patient_id_fk
		foreign key (patient_id) references patient (patient_id)
			on update cascade on delete cascade
);

create table if not exists provider
(
	provider_id int auto_increment
		primary key,
	provider_name varchar(255) not null,
	provider_phone varchar(10) not null,
	provider_email varchar(255) not null,
	provider_address varchar(255) not null,
	provider_type varchar(255) not null,
	provider_longitude double(9,6) not null,
	provider_latitude double(9,6) not null,
	constraint provider_user_user_id_fk
		foreign key (provider_id) references user (user_id)
			on update cascade on delete cascade,
	constraint provider_user_user_name_fk
		foreign key (provider_email) references user (user_name)
			on update cascade on delete cascade
);

create table if not exists provider_available_time
(
	pat_id int auto_increment
		primary key,
	provider_id int not null,
	w_id int null,
	t_id int not null,
	constraint provider_available_time_provider_provider_id_fk
		foreign key (provider_id) references provider (provider_id)
			on update cascade on delete cascade
);

create table if not exists appointment
(
	appointment_id int auto_increment
		primary key,
	patient_id int not null,
	pat_id int not null,
	status enum('pending', 'accepted', 'declined', 'cancelled', 'vaccinated', 'noshow') not null,
	constraint appointment_patient_patient_id_fk
		foreign key (patient_id) references patient (patient_id)
			on update cascade on delete cascade,
	constraint appointment_provider_available_time_pat_id_fk
		foreign key (pat_id) references provider_available_time (pat_id)
			on update cascade on delete cascade
);

create index provider_available_time_time_slot_t_id_fk
	on provider_available_time (t_id);

