CREATE TABLE tx_clubdata_domain_model_program (
    title varchar(255) DEFAULT '' NOT NULL,
    intern smallint(5) unsigned DEFAULT '0' NOT NULL,
    avoid_nl smallint(5) unsigned DEFAULT '0' NOT NULL,
    sub_title varchar(255) DEFAULT '' NOT NULL,
    sec_sub_title varchar(255) DEFAULT '' NOT NULL,
    datetime int(11) DEFAULT '0' NOT NULL,
    hide_date smallint(5) unsigned DEFAULT '0' NOT NULL,
    entrance varchar(255) DEFAULT '' NOT NULL,
    description text,
    seating smallint(5) unsigned DEFAULT '0' NOT NULL,
    venue varchar(255) DEFAULT '' NOT NULL,
    slug varchar(2048) DEFAULT '' NOT NULL,
    picture int(11) unsigned NOT NULL default '0',
    highlight smallint(5) unsigned DEFAULT '0' NOT NULL,
    perm_highlight smallint(5) unsigned DEFAULT '0' NOT NULL,
    cat_price_a varchar(255) DEFAULT '' NOT NULL,
    price_a varchar(255) DEFAULT '' NOT NULL,
    cat_price_b varchar(255) DEFAULT '' NOT NULL,
    price_b varchar(255) DEFAULT '' NOT NULL,
    cat_price_c varchar(255) DEFAULT '' NOT NULL,
    price_c varchar(255) DEFAULT '' NOT NULL,
    ticket_link varchar(255) DEFAULT '' NOT NULL,
    pre_sales text,
    internal_info text,
    visitors varchar(255) DEFAULT '' NOT NULL,
    max_tickets int(11) DEFAULT '0' NOT NULL,
    sold_tickets int(11) DEFAULT '0' NOT NULL,
    service_bar_num int(11) DEFAULT '0' NOT NULL,
    state_text varchar(255) DEFAULT '' NOT NULL,
    reduction smallint(5) unsigned DEFAULT '0' NOT NULL,
    state int(11) unsigned DEFAULT '0',
    links int(11) unsigned DEFAULT '0' NOT NULL,
    services int(11) unsigned DEFAULT '0' NOT NULL,
    categories int(11) unsigned DEFAULT '0' NOT NULL,
    seatings int(11) unsigned DEFAULT '0',
    genre varchar(255) DEFAULT '' NOT NULL,
    festival smallint(5) unsigned DEFAULT '0' NOT NULL,
    noservice smallint(5) unsigned DEFAULT '0' NOT NULL,
    flags int(11) unsigned DEFAULT '0' NOT NULL
);

CREATE TABLE tx_clubdata_domain_model_state (
    title varchar(255) DEFAULT '' NOT NULL
);

CREATE TABLE tx_clubdata_domain_model_link (
    title varchar(255) DEFAULT '' NOT NULL
);

CREATE TABLE tx_clubdata_domain_model_service (
    title varchar(255) DEFAULT '' NOT NULL
);

CREATE TABLE tx_clubdata_domain_model_seating (
    title varchar(255) DEFAULT '' NOT NULL
);

CREATE TABLE tx_clubdata_program_link_mm (
    title varchar(255) DEFAULT '' NOT NULL,
    program int(11) DEFAULT '0' NOT NULL,
    link int(11) unsigned DEFAULT '0'
);

CREATE TABLE tx_clubdata_program_service_user_mm (
    remark varchar(255) DEFAULT '' NOT NULL,
    program int(11) unsigned DEFAULT '0',
    service int(11) unsigned DEFAULT '0',
    user int(11) unsigned DEFAULT '0'
);

CREATE TABLE fe_users (
    salutation varchar(255) DEFAULT '' NOT NULL
);

CREATE TABLE sys_category (
    children int(11) unsigned DEFAULT '0' NOT NULL
);
