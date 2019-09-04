DROP TABLE IF EXISTS `vote`;

CREATE TABLE `vote` (
  `identity` VARCHAR(36) NOT NULL UNIQUE,
  `hash` VARCHAR(32) NOT NULL,
  `rate` INTEGER NOT NULL
);

INSERT INTO vote (identity, hash, rate) VALUES ('cf3b433f-a395-465b-8705-abe0ba94f106', 'a4f196b32ee43803f8c17fbcc20c1c68', 2);
INSERT INTO vote (identity, hash, rate) VALUES ('2b7363a8-b3e4-421c-ad25-94e936c8562f', 'a4f196b32ee43803f8c17fbcc20c1c68', 3);
INSERT INTO vote (identity, hash, rate) VALUES ('b354dcd9-f7ae-415b-93fb-6084be35893d', 'a4f196b32ee43803f8c17fbcc20c1c68', 5);
INSERT INTO vote (identity, hash, rate) VALUES ('88193910-092a-43d4-83fc-196dc6340cc5', 'a4f196b32ee43803f8c17fbcc20c1c68', 2);
INSERT INTO vote (identity, hash, rate) VALUES ('1836a1da-9061-41bf-91af-967101fd2cda', 'a4f196b32ee43803f8c17fbcc20c1c68', 3);