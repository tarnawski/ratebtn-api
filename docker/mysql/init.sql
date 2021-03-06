DROP TABLE IF EXISTS `vote`;

CREATE TABLE `vote` (
  `identity` VARCHAR(36) NOT NULL UNIQUE,
  `url` VARCHAR(255) NOT NULL,
  `rate` INTEGER NOT NULL,
  `fingerprint` VARCHAR(36) NOT NULL,
  `created_at` datetime NOT NULL
);

INSERT INTO vote (identity, url, rate, fingerprint, created_at) VALUES ('cf3b433f-a395-465b-8705-abe0ba94f106', 'www.example.com', 2, 'abe0ba94f106', '2019-06-17 18:24:21');
INSERT INTO vote (identity, url, rate, fingerprint, created_at) VALUES ('2b7363a8-b3e4-421c-ad25-94e936c8562f', 'www.example.com', 3, '94e936c8562f', '2019-06-17 18:24:21');
INSERT INTO vote (identity, url, rate, fingerprint, created_at) VALUES ('b354dcd9-f7ae-415b-93fb-6084be35893d', 'www.example.com', 5, '6084be35893d', '2019-06-17 18:24:21');
INSERT INTO vote (identity, url, rate, fingerprint, created_at) VALUES ('88193910-092a-43d4-83fc-196dc6340cc5', 'www.example.com', 2, '196dc6340cc5', '2019-06-17 18:24:21');
INSERT INTO vote (identity, url, rate, fingerprint, created_at) VALUES ('1836a1da-9061-41bf-91af-967101fd2cda', 'www.example.com', 3, '967101fd2cda', '2019-06-17 18:24:21');