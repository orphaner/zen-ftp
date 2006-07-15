CREATE TABLE users (
       "User" character varying(16) DEFAULT ''::character varying NOT NULL,
       "Password" character varying(32) DEFAULT ''::character varying NOT NULL,
       "Uid" integer DEFAULT 501 NOT NULL, 
       "Gid" integer DEFAULT 501 NOT NULL, 
       "Dir" character varying(128) DEFAULT ''::character varying NOT NULL, 
       "QuotaFiles" integer DEFAULT 500 NOT NULL,
       "QuotaSize" integer DEFAULT 30 NOT NULL, 
       "ULBandwidth" integer DEFAULT 80 NOT NULL,
       "DLBandwidth" integer DEFAULT 80 NOT NULL, 
       "Ipaddress" character varying(15) DEFAULT '*'::character varying NOT NULL, 
       "Comment" character varying,
       "Status" integer DEFAULT 1 NOT NULL, 
       "ULRatio" integer DEFAULT 1 NOT NULL,
       "DLRatio" integer DEFAULT 1 NOT NULL);
ALTER TABLE ONLY users ADD CONSTRAINT users_pkey PRIMARY KEY ("User");