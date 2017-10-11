## Base schema


```sql
CREATE TABLE contact_bruger_address
(
  id                INT AUTO_INCREMENT
    PRIMARY KEY,
  adresse_no        INT          NULL,
  aktiv_code        VARCHAR(1)   NULL,
  dannelses_dato    DATETIME     NULL,
  slette_tid        DATETIME     NULL,
  alt_mail_id       VARCHAR(100) NULL,
  verification_code VARCHAR(100) NULL,
  oprindelse        VARCHAR(50)  NULL,
  certificat_id     INT          NULL,
  status            VARCHAR(1)   NULL,
  sidst_anvendt     VARCHAR(100)     NULL,
  bruger_id         INT          NULL,
  system_id         INT          NULL,
  antal             INT          NULL,
  bekraft_udsendt   VARCHAR(100)     NULL,
  adress_type INT          NULL,
  adresse     VARCHAR(254) NULL
);

CREATE TABLE contact_fritag
(
  id                 INT AUTO_INCREMENT
    PRIMARY KEY,
  bruger_id          INT         NULL,
  oprettet_tid       VARCHAR(100)    NULL,
  ophaevet_tid       VARCHAR(100)    NULL,
  udloeb_data        VARCHAR(100)    NULL,
  ful_magt           VARCHAR(1)  NULL,
  fuldmagt_identtype VARCHAR(20) NULL,
  fremsend_uaabnet   VARCHAR(1)  NULL,
  arrsag             INT         NULL
);

CREATE TABLE contacts
(
  id                    INT AUTO_INCREMENT
    PRIMARY KEY,
  contact_id            INT        NULL,
  foedtmmaa             VARCHAR(4) NULL,
  logon_time            VARCHAR(100)   NULL,
  dk_status             INT        NULL,
  dkal_accept_time      VARCHAR(100)   NULL,
  dkal_tilmeldt_alt     VARCHAR(1) NULL,
  sidst_opdagteret      VARCHAR(100)   NULL,
  eboks_rollestyring    VARCHAR(1) NULL,
  nem_sms_status        INT        NULL,
  nem_sms_accepted_time VARCHAR(100)   NULL,
  nem_sms_tilemdt_alt   VARCHAR(1) NULL,
  dkal_tilmeld_tid      VARCHAR(100)   NULL,
  nem_sms_timeld_tid    VARCHAR(100)   NULL,
  sparret               VARCHAR(1) NULL,
  sparret_updated_at    VARCHAR(100)   NULL
);
CREATE TABLE contact_bruger_address
(
  id                INT AUTO_INCREMENT
    PRIMARY KEY,
  adresse_no        INT          NULL,
  aktiv_code        VARCHAR(1)   NULL,
  dannelses_dato    DATETIME     NULL,
  slette_tid        DATETIME     NULL,
  alt_mail_id       VARCHAR(100) NULL,
  verification_code VARCHAR(100) NULL,
  oprindelse        VARCHAR(50)  NULL,
  certificat_id     INT          NULL,
  status            VARCHAR(1)   NULL,
  sidst_anvendt     VARCHAR(100)     NULL,
  bruger_id         INT          NULL,
  system_id         INT          NULL,
  antal             INT          NULL,
  bekraft_udsendt   VARCHAR(100)     NULL,
  adress_type INT          NULL,
  adresse     VARCHAR(254) NULL
);

CREATE TABLE contact_fritag
(
  id                 INT AUTO_INCREMENT
    PRIMARY KEY,
  bruger_id          INT         NULL,
  oprettet_tid       VARCHAR(100)    NULL,
  ophaevet_tid       VARCHAR(100)    NULL,
  udloeb_data        VARCHAR(100)    NULL,
  ful_magt           VARCHAR(1)  NULL,
  fuldmagt_identtype VARCHAR(20) NULL,
  fremsend_uaabnet   VARCHAR(1)  NULL,
  arrsag             INT         NULL
);

CREATE TABLE contacts
(
  id                    INT AUTO_INCREMENT
    PRIMARY KEY,
  contact_id            INT        NULL,
  foedtmmaa             VARCHAR(4) NULL,
  logon_time            VARCHAR(100)   NULL,
  dk_status             INT        NULL,
  dkal_accept_time      VARCHAR(100)   NULL,
  dkal_tilmeldt_alt     VARCHAR(1) NULL,
  sidst_opdagteret      VARCHAR(100)   NULL,
  eboks_rollestyring    VARCHAR(1) NULL,
  nem_sms_status        INT        NULL,
  nem_sms_accepted_time VARCHAR(100)   NULL,
  nem_sms_tilemdt_alt   VARCHAR(1) NULL,
  dkal_tilmeld_tid      VARCHAR(100)   NULL,
  nem_sms_timeld_tid    VARCHAR(100)   NULL,
  sparret               VARCHAR(1) NULL,
  sparret_updated_at    VARCHAR(100)   NULL
);

```