DROP TABLE IF EXISTS `vote`;

CREATE TABLE `vote` (
  `identity` VARCHAR(36) NOT NULL UNIQUE,
  `url` VARCHAR(255) NOT NULL,
  `rate` INTEGER NOT NULL
);

INSERT INTO vote (identity, url, rate) VALUES ('cf3b433f-a395-465b-8705-abe0ba94f106', 'www.example.com', 2);
INSERT INTO vote (identity, url, rate) VALUES ('2b7363a8-b3e4-421c-ad25-94e936c8562f', 'www.example.com', 3);
INSERT INTO vote (identity, url, rate) VALUES ('b354dcd9-f7ae-415b-93fb-6084be35893d', 'www.example.com', 5);
INSERT INTO vote (identity, url, rate) VALUES ('88193910-092a-43d4-83fc-196dc6340cc5', 'www.example.com', 2);
INSERT INTO vote (identity, url, rate) VALUES ('1836a1da-9061-41bf-91af-967101fd2cda', 'www.example.com', 3);