-- *********************************************
-- * SQL MySQL generation                      
-- *--------------------------------------------
-- * DB-MAIN version: 11.0.2              
-- * Generator date: Sep 14 2021              
-- * Generation date: Mon May 22 14:01:02 2023 
-- * LUN file: C:\Users\davassayah\Desktop\TPI_David_Assayah_2023\DB\TPI_David_Assayah_2023(DB)V2.lun 
-- * Schema: TPI V4 MCD/1 
-- ********************************************* 


-- Database Section
-- ________________ 

create database TPI V4 MCD;
use TPI V4 MCD;


-- Tables Section
-- _____________ 

create table t_card (
     idCard int not null,
     carName varchar(50) not null,
     carDate int not null,
     carCredits int not null,
     carCondition varchar(10) not null,
     carDescription varchar(200) not null,
     carStatus int not null,
     carPhoto varchar(50) not null,
     idUser int not null,
     idOrder int not null,
     idCollection int not null,
     constraint ID_t_card_ID primary key (idCard));

create table t_collection (
     idCollection int not null,
     colName varchar(20) not null,
     constraint ID_t_collection_ID primary key (idCollection));

create table t_order (
     idOrder int not null,
     ordDescription varchar(200) not null,
     ordStatus int not null,
     idUser int not null,
     constraint ID_t_order_ID primary key (idOrder));

create table t_user (
     idUser int not null,
     useLogin char(1) not null,
     useFirstName varchar(50) not null,
     useName varchar(50) not null,
     useLocality varchar(50) not null,
     usePostalCode varchar(5) not null,
     useStreetName varchar(50) not null,
     useStreetNumber varchar(5) not null,
     usePassword varchar(5) not null,
     useCredits int not null,
     useRole int not null,
     constraint ID_t_user_ID primary key (idUser));


-- Constraints Section
-- ___________________ 

alter table t_card add constraint FKt_possess_FK
     foreign key (idUser)
     references t_user (idUser);

alter table t_card add constraint FKt_contain_FK
     foreign key (idOrder)
     references t_order (idOrder);

alter table t_card add constraint FKt_belong_FK
     foreign key (idCollection)
     references t_collection (idCollection);

-- Not implemented
-- alter table t_collection add constraint ID_t_collection_CHK
--     check(exists(select * from t_card
--                  where t_card.idCollection = idCollection)); 

-- Not implemented
-- alter table t_order add constraint ID_t_order_CHK
--     check(exists(select * from t_card
--                  where t_card.idOrder = idOrder)); 

alter table t_order add constraint FKt_orderCard_FK
     foreign key (idUser)
     references t_user (idUser);


-- Index Section
-- _____________ 

create unique index ID_t_card_IND
     on t_card (idCard);

create index FKt_possess_IND
     on t_card (idUser);

create index FKt_contain_IND
     on t_card (idOrder);

create index FKt_belong_IND
     on t_card (idCollection);

create unique index ID_t_collection_IND
     on t_collection (idCollection);

create unique index ID_t_order_IND
     on t_order (idOrder);

create index FKt_orderCard_IND
     on t_order (idUser);

create unique index ID_t_user_IND
     on t_user (idUser);

