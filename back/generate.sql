CREATE TABLE location (
    location_id BIGINT UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
    building INT UNSIGNED NOT NULL,
    street VARCHAR(255) NOT NULL
);

CREATE TABLE provider (
    provider_id BIGINT UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    location_id BIGINT UNSIGNED NOT NULL,
    notification_datetime DATETIME,
    status ENUM('PASSED', 'DID_NOT_PASS', 'NOT_COMPLETE') NOT NULL
);

CREATE TABLE `call` (
    call_id BIGINT UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
    sid VARCHAR(255) NOT NULL,
    provider_id BIGINT UNSIGNED NOT NULL,
    start_datetime DATETIME NOT NULL,
    end_datetime DATETIME,
    status ENUM('CALLING_ADMIN', 'ADMIN_CONNECTED', 'ADMIN_FAILED', 'CALLING_PROVIDER', 'BOTH_CONNECTED', 'PROVIDER_FAILED', 'COMPLETED') NOT NULL
);

ALTER TABLE provider ADD FOREIGN KEY (location_id) REFERENCES location(location_id);
ALTER TABLE `call` ADD FOREIGN KEY (provider_id) REFERENCES provider(provider_id);

INSERT INTO location (location_id, building, street) VALUES
(1,  1962,  'Dagota Avenue'),
(2,  29,    'Abwuf Extension'),
(3,  1836,  'Agha Road'),
(4,  756,   'Gojub Drive'),
(5,  1845,  'Sodim Park'),
(6,  1962,  'Bowi View'),
(7,  1573,  'Ipumod Square'),
(8,  501,   'Fisfik View'),
(9,  485,   'Lolki Extension');

INSERT INTO provider (provider_id, name, location_id, notification_datetime, status) VALUES
(1,  'Matthew Dunn',    1,  '2020-10-22 01:09:11', 'PASSED'),
(2,  'Verna Mathis',    2,  '2020-09-14 18:38:27', 'PASSED'),
(3,  'Mamie Larson',    3,  '2020-07-19 03:26:07', 'NOT_COMPLETE'),
(4,  'Leon Jordan',     4,  '2020-08-14 01:22:47', 'NOT_COMPLETE'),
(5,  'Callie Wagner',   5,  '2020-08-03 17:23:02', 'DID_NOT_PASS'),
(6,  'Hunter Sandoval', 6,  '2020-10-05 14:53:36', 'NOT_COMPLETE'),
(7,  'Phoebe Wright',   7,  '2020-10-05 13:41:50', 'DID_NOT_PASS'),
(8,  'Millie Abbott',   8,  '2020-10-05 17:19:24', 'NOT_COMPLETE'),
(9,  'Kate Stokes',     9,  '2020-10-05 16:02:11', 'PASSED'),
(10, 'Genevieve Sims',  4,  '2020-08-14 01:22:12', 'NOT_COMPLETE'),
(11, 'Elmer Rios',      5,  '2020-08-03 17:23:41', 'DID_NOT_PASS'),
(12, 'Isabelle Terry',  6,  '2020-10-05 14:25:55', 'NOT_COMPLETE'),
(13, 'Nallie Walsh',    7,  '2020-10-05 13:58:40', 'DID_NOT_PASS'),
(14, 'Jane Kim',        8,  '2020-10-05 17:20:02', 'NOT_COMPLETE'),
(15, 'Lennie Leonard',  9,  '2020-10-05 16:13:29', 'PASSED');
