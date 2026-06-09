
SET FOREIGN_KEY_CHECKS = 0;

CREATE TABLE gids_leefgebied (
    leefgebied_id INT NOT NULL AUTO_INCREMENT,
    naam_leefgebied VARCHAR(255) NOT NULL,
    beschrijving_leefgebied TEXT NULL,
    sort_order INT NULL,

    PRIMARY KEY (leefgebied_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE gids_functie (
    functie_id INT NOT NULL AUTO_INCREMENT,
    leefgebied_id INT NOT NULL,
    naam_functie VARCHAR(255) NOT NULL,
    beschrijving_functie TEXT NULL,
    sort_order INT NULL,

    PRIMARY KEY (functie_id),
    KEY idx_functie_leefgebied (leefgebied_id),

    FOREIGN KEY (leefgebied_id)
        REFERENCES gids_leefgebied(leefgebied_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE gids_aandachtspunt (
    aandachtspunt_id INT NOT NULL AUTO_INCREMENT,
    functie_id INT NOT NULL,
    sort_order INT NULL,
    aandachtspunt VARCHAR(255) NOT NULL,
    toelichting TEXT NULL,
    scan_tekst TEXT NULL,
    advies_tekst TEXT NULL,

    PRIMARY KEY (aandachtspunt_id),
    KEY idx_aandachtspunt_functie (functie_id),

    FOREIGN KEY (functie_id)
        REFERENCES gids_functie(functie_id)
        ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE gids_hulpbron (
    hulpbron_id INT NOT NULL AUTO_INCREMENT,
    hulpbron VARCHAR(255) NOT NULL,
    toelichting TEXT NULL,

    PRIMARY KEY (hulpbron_id),
    UNIQUE KEY uniq_hulpbron (hulpbron)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE gids_leefgebied_hulpbron (
    leefgebied_hulpbron_id INT NOT NULL AUTO_INCREMENT,
    leefgebied_id INT NOT NULL,
    hulpbron_id INT NOT NULL,
    sort_order INT NOT NULL,

    PRIMARY KEY (leefgebied_hulpbron_id),
    UNIQUE KEY uniq_leefgebied_hulpbron (leefgebied_id, hulpbron_id),
    KEY idx_hulpbronnen_leefgebied (leefgebied_id),
    KEY idx_hulpbronnen_hulpbron (hulpbron_id),

    FOREIGN KEY (leefgebied_id)
        REFERENCES gids_leefgebied(leefgebied_id)
        ON DELETE CASCADE,

    FOREIGN KEY (hulpbron_id)
        REFERENCES gids_hulpbron(hulpbron_id)
        ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE gids_urls (
    url_id INT NOT NULL AUTO_INCREMENT,
    leefgebied_id INT NOT NULL,
    leefgebied_url VARCHAR(255) NOT NULL,

    PRIMARY KEY (url_id),
    KEY idx_urls_leefgebied (leefgebied_id),

    FOREIGN KEY (leefgebied_id)
        REFERENCES gids_leefgebied(leefgebied_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE gids_users (
    user_id INT NOT NULL AUTO_INCREMENT,
    first_name VARCHAR(100) NULL,
    last_name VARCHAR(100) NULL,
    email VARCHAR(255) NULL,
    google_id VARCHAR(255) NULL,
    password VARCHAR(255) NULL,
    is_admin BOOLEAN NOT NULL DEFAULT 0,
    is_verified TINYINT(1) NOT NULL DEFAULT 0,
    account_type VARCHAR(255) NULL,
    profile_picture VARCHAR(500) NULL COMMENT 'URL van de Google profielfoto',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    PRIMARY KEY (user_id),
    UNIQUE KEY uniq_users_email (email),
    UNIQUE KEY uniq_users_google_id (google_id),
    KEY idx_users_email (email),
    KEY idx_users_is_verified (is_verified)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE gids_role (
    id INT NOT NULL AUTO_INCREMENT,
    name VARCHAR(50) NOT NULL,
    description TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    PRIMARY KEY (id),
    UNIQUE KEY uniq_role_name (name),
    KEY idx_role_name (name)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE gids_user_role (
    id INT NOT NULL AUTO_INCREMENT,
    user_id INT NOT NULL,
    role_id INT NOT NULL,
    assigned_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    PRIMARY KEY (id),
    UNIQUE KEY uniq_user_role (user_id, role_id),
    KEY idx_user_role_user_id (user_id),
    KEY idx_user_role_role_id (role_id),

    FOREIGN KEY (user_id)
        REFERENCES gids_users(user_id)
        ON DELETE CASCADE,

    FOREIGN KEY (role_id)
        REFERENCES gids_role(id)
        ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE gids_rate_limits (
    id INT NOT NULL AUTO_INCREMENT,
    rate_key CHAR(64) NOT NULL,
    scope VARCHAR(64) NOT NULL,
    attempts INT NOT NULL DEFAULT 0,
    window_start DATETIME NOT NULL,
    blocked_until DATETIME NULL,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    PRIMARY KEY (id),
    UNIQUE KEY uniq_rate_key_scope (rate_key, scope),
    KEY idx_scope_updated (scope, updated_at),
    KEY idx_blocked_until (blocked_until)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE gids_otp_codes (
    id INT NOT NULL AUTO_INCREMENT,
    email VARCHAR(255) NOT NULL,
    code VARCHAR(6) NOT NULL,
    created_at INT UNSIGNED NOT NULL,
    expires_at INT UNSIGNED NOT NULL,
    used_at INT UNSIGNED NULL,

    PRIMARY KEY (id),
    KEY idx_otp_email (email),
    KEY idx_otp_code (code),
    KEY idx_otp_expires_at (expires_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE gids_vragenlijst_role (
    id INT NOT NULL AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,

    PRIMARY KEY (id),
    UNIQUE KEY uniq_vragenlijst_role_name (name)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE gids_vragenlijst_question_type (
    id INT NOT NULL AUTO_INCREMENT,
    name VARCHAR(50) NOT NULL,
    has_options TINYINT(1) DEFAULT 0,

    PRIMARY KEY (id),
    UNIQUE KEY uniq_question_type_name (name)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE gids_vragenlijst_question (
    id INT NOT NULL AUTO_INCREMENT,
    role_id INT NOT NULL,
    question_key VARCHAR(100) NOT NULL,
    label VARCHAR(255) NOT NULL,
    question_type_id INT NOT NULL,
    default_value JSON NULL,
    sort_order INT NOT NULL,

    PRIMARY KEY (id),
    UNIQUE KEY uniq_role_key (role_id, question_key),
    KEY idx_question_role (role_id),
    KEY idx_question_type (question_type_id),

    FOREIGN KEY (role_id)
        REFERENCES gids_vragenlijst_role(id)
        ON DELETE CASCADE,

    FOREIGN KEY (question_type_id)
        REFERENCES gids_vragenlijst_question_type(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE gids_vragenlijst_option (
    id INT NOT NULL AUTO_INCREMENT,
    question_id INT NOT NULL,
    option_value VARCHAR(100) NOT NULL,
    label VARCHAR(255) NOT NULL,
    sort_order INT NOT NULL,

    PRIMARY KEY (id),
    UNIQUE KEY uniq_question_value (question_id, option_value),
    KEY idx_option_question (question_id),

    FOREIGN KEY (question_id)
        REFERENCES gids_vragenlijst_question(id)
        ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE gids_scans_data (
    scan_data_id INT NOT NULL AUTO_INCREMENT,
    scan_data JSON NOT NULL,
    user_cookie_id VARCHAR(64) NOT NULL,
    rol ENUM('jezelf', 'naaste', 'vrijwilliger', 'professional') NOT NULL,
    sub_rol VARCHAR(100) NULL,
    organisatie_naam VARCHAR(255) NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    user_id INT NULL,

    PRIMARY KEY (scan_data_id),
    KEY idx_scans_data_userid (user_id),
    KEY idx_scans_data_rol (rol),
    KEY idx_scans_data_created (created_at),

    FOREIGN KEY (user_id)
        REFERENCES gids_users(user_id)
        ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE gids_verdieping_vragen (
    verdiepingsvraag_id INT NOT NULL AUTO_INCREMENT,
    vraag VARCHAR(255) NULL,
    aandachtspunt_id INT NULL,

    PRIMARY KEY (verdiepingsvraag_id),
    KEY idx_verdieping_aandachtspunt (aandachtspunt_id),

    FOREIGN KEY (aandachtspunt_id)
        REFERENCES gids_aandachtspunt(aandachtspunt_id)
        ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE gids_organisatie (
    organisatie_id INT NOT NULL AUTO_INCREMENT,
    naam VARCHAR(255) NOT NULL,
    adres VARCHAR(255) NULL,
    telefoon VARCHAR(50) NULL,
    email VARCHAR(255) NULL,
    website VARCHAR(255) NULL,

    PRIMARY KEY (organisatie_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE gids_links (
    link_id INT NOT NULL AUTO_INCREMENT,
    titel VARCHAR(255) NOT NULL,
    url VARCHAR(500) NOT NULL,
    belangrijk_bericht TEXT NULL,
    toon_popup TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    PRIMARY KEY (link_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE gids_aandachtspunt_koppeltabel (
    id INT NOT NULL AUTO_INCREMENT,
    link_id INT NOT NULL,
    aandachtspunt_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    PRIMARY KEY (id),
    UNIQUE KEY uniq_link_aandachtspunt (link_id, aandachtspunt_id),
    KEY idx_koppeltabel_aandachtspunt (aandachtspunt_id),
    KEY idx_koppeltabel_link (link_id),

    FOREIGN KEY (aandachtspunt_id)
        REFERENCES gids_aandachtspunt(aandachtspunt_id)
        ON DELETE CASCADE,

    FOREIGN KEY (link_id)
        REFERENCES gids_links(link_id)
        ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE gids_bezoekers (
    id INT NOT NULL AUTO_INCREMENT,
    cookie_id VARCHAR(45) NULL,
    bezoektijd TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO gids_urls (leefgebied_id, leefgebied_url)
VALUES
    (1, 'zorg_voor_jezelf'),
    (2, 'contact-met-anderen'),
    (3, 'samenwonen'),
    (4, 'woning'),
    (5, 'meedoen'),
    (6, 'leren-en-werken'),
    (7, 'geld');

INSERT INTO gids_hulpbron (hulpbron, toelichting)
VALUES
    ('Iemand uit mijn omgeving (partner, familielid, vriend)', NULL),
    ('Een mantelzorger', NULL),
    ('Een vrijwilliger', NULL),
    ('Iemand die dit zelf heeft meegemaakt (ervaringsdeskundige)', NULL),
    ('Een hulpmiddel', NULL),
    ('Een professional', NULL);

INSERT INTO gids_leefgebied_hulpbron (leefgebied_id, hulpbron_id, sort_order)
VALUES
    (1, 1, 1), (1, 2, 2), (1, 3, 3), (1, 4, 4), (1, 5, 5), (1, 6, 6),
    (2, 1, 1), (2, 2, 2), (2, 3, 3), (2, 4, 4), (2, 5, 5), (2, 6, 6),
    (3, 1, 1), (3, 2, 2), (3, 3, 3), (3, 4, 4), (3, 5, 5), (3, 6, 6),
    (4, 1, 1), (4, 2, 2), (4, 3, 3), (4, 4, 4), (4, 5, 5), (4, 6, 6),
    (5, 1, 1), (5, 2, 2), (5, 3, 3), (5, 4, 4), (5, 5, 5), (5, 6, 6),
    (6, 1, 1), (6, 2, 2), (6, 3, 3), (6, 4, 4), (6, 5, 5), (6, 6, 6),
    (7, 1, 1), (7, 2, 2), (7, 3, 3), (7, 4, 4), (7, 5, 5), (7, 6, 6);

INSERT INTO gids_vragenlijst_role (id, name)
VALUES
    (1, 'mijzelf'),
    (2, 'een naaste'),
    (3, 'mijn (hulp) maatje'),
    (4, 'professional');

INSERT INTO gids_vragenlijst_question_type (id, name, has_options)
VALUES
    (1, 'radio', 1),
    (2, 'number', 0),
    (3, 'checkbox', 1),
    (4, 'textarea', 0),
    (5, 'text', 0);

INSERT INTO gids_vragenlijst_question (id, role_id, question_key, label, question_type_id, default_value, sort_order) VALUES
    (1, 1, 'geslacht', 'Mijn geslacht', 1, NULL, 1),
    (2, 1, 'leeftijd', 'Mijn leeftijd', 2, NULL, 2),
    (3, 1, 'gezinssituatie', 'Mijn gezinssituatie', 1, NULL, 3),
    (4, 1, 'mobiliteit', 'Mijn mobiliteit', 1, NULL, 4),
    (5, 1, 'vervoer', 'Mijn vervoer (meerdere opties mogelijk)', 3, NULL, 5),
    (6, 1, 'parkeerkaart', 'Gehandicaptenparkeerkaart', 1, NULL, 6),
    (7, 1, 'netwerk', 'Mijn persoonlijk netwerk (meerdere opties mogelijk)', 3, NULL, 7),
    (8, 1, 'woning', 'Mijn woning', 1, NULL, 8),
    (9, 1, 'overige', 'Overige toelichting', 4, NULL, 9),

    (10, 2, 'geslacht', 'Ik zoek hulp voor een', 1, NULL, 1),
    (11, 2, 'leeftijd', 'Haar/zijn leeftijd is', 2, NULL, 2),
    (12, 2, 'gezinssituatie', 'De gezinssituatie van de persoon', 1, NULL, 3),
    (13, 2, 'mobiliteit', 'Mobiliteit van de persoon', 1, NULL, 4),
    (14, 2, 'vervoer', 'Eigen vervoer van de persoon (meerdere opties mogelijk)', 3, NULL, 5),
    (15, 2, 'parkeerkaart', 'Is er een gehandicaptenparkeerkaart', 1, NULL, 6),
    (16, 2, 'netwerk', 'Het persoonlijk netwerk van de persoon (meerdere opties mogelijk)', 3, NULL, 7),
    (17, 2, 'woning', 'De woning van de persoon', 1, NULL, 8),

    (18, 3, 'geslacht', 'Ik zoek hulp voor een', 1, NULL, 1),
    (19, 3, 'leeftijd', 'Haar/zijn leeftijd is', 2, NULL, 2),
    (20, 3, 'gezinssituatie', 'De gezinssituatie van de persoon', 1, NULL, 3),
    (21, 3, 'mobiliteit', 'Mobiliteit van de persoon', 1, NULL, 4),
    (22, 3, 'vervoer', 'Eigen vervoer van de persoon (meerdere opties mogelijk)', 3, NULL, 5),
    (23, 3, 'parkeerkaart', 'Is er een gehandicaptenparkeerkaart', 1, '"nee"', 6),
    (24, 3, 'netwerk', 'Het persoonlijk netwerk van de persoon (meerdere opties mogelijk)', 3, NULL, 7),
    (25, 3, 'woning', 'De woning van de persoon', 1, NULL, 8),
    (26, 3, 'overleg_instanties', 'Overleg met instanties', 1, NULL, 9),
    (27, 3, 'aard_beperking', 'Aard van beperking (meerdere opties mogelijk)', 3, NULL, 10),
    (28, 3, 'computer', 'Computervaardigheid', 1, NULL, 11),
    (29, 3, 'taalLezen', 'Lezen (NL)', 1, NULL, 12),
    (30, 3, 'taalSchrijven', 'Schrijven (NL)', 1, NULL, 13),
    (31, 3, 'taalSpreken', 'Spreken (NL)', 1, NULL, 14),
    (32, 3, 'overige', 'Zijn er nog meer relevante punten?', 4, NULL, 15),

    (33, 4, 'geslacht', 'Ik zoek hulp voor een', 1, NULL, 1),
    (34, 4, 'leeftijd', 'Haar/zijn leeftijd is', 2, NULL, 2),
    (35, 4, 'gezinssituatie', 'De gezinssituatie van de persoon', 1, NULL, 3),
    (36, 4, 'mobiliteit', 'Mobiliteit van de persoon', 1, '"geen_probleem"', 4),
    (37, 4, 'vervoer', 'Eigen vervoer van de persoon (meerdere opties mogelijk)', 3, '["geen"]', 5),
    (38, 4, 'parkeerkaart', 'Is er een gehandicaptenparkeerkaart', 1, '"nee"', 6),
    (39, 4, 'netwerk', 'Het persoonlijk netwerk van de persoon (meerdere opties mogelijk)', 3, NULL, 7),
    (40, 4, 'woning', 'De woning van de persoon', 1, NULL, 8),
    (41, 4, 'computer', 'Computervaardigheid', 1, NULL, 9),
    (42, 4, 'taalLezen', 'Lezen (NL)', 1, NULL, 10),
    (43, 4, 'taalSchrijven', 'Schrijven (NL)', 1, NULL, 11),
    (44, 4, 'taalSpreken', 'Spreken (NL)', 1, NULL, 12),
    (45, 4, 'overleg_instanties', 'Overleg met instanties', 1, NULL, 13),
    (46, 4, 'aard_beperking', 'Aard van beperking (meerdere opties mogelijk)', 3, NULL, 14),
    (47, 4, 'arts_vastgesteld', 'Zijn beperkingen door arts vastgesteld?', 1, '"nee"', 15),
    (48, 4, 'vaardigheden_getraind', 'Worden ontbrekende vaardigheden al getraind?', 1, '"nee"', 16),
    (49, 4, 'zorgpolis', 'Zorgpolis', 1, NULL, 17),
    (50, 4, 'zorgindicatie_types', 'Zorgindicatie', 3, '["nee"]', 18),
    (51, 4, 'overige', 'Zijn er nog meer relevante punten?', 4, NULL, 19),

    (52, 1, 'naam_organisatie_of_persoon', 'Mijn naam of organisatie', 5, NULL, 0),
    (53, 1, 'naam_hulpvrager', 'naam hulpvrager (optioneel)', 5, NULL, 1),
    (54, 2, 'naam_organisatie_of_persoon', 'naam persoon of organisatie', 5, NULL, 0),
    (55, 2, 'naam_hulpvrager', 'naam hulpvrager (optioneel)', 5, NULL, 1),
    (56, 3, 'naam_organisatie_of_persoon', 'naam persoon of organisatie', 5, NULL, 0),
    (57, 3, 'naam_hulpvrager', 'naam hulpvrager (optioneel)', 5, NULL, 1),
    (58, 4, 'naam_organisatie_of_persoon', 'naam persoon of organisatie', 5, NULL, 0),
    (59, 4, 'naam_hulpvrager', 'naam hulpvrager (optioneel)', 5, NULL, 1);


INSERT INTO gids_vragenlijst_option (question_id, option_value, label, sort_order) VALUES
    (1, 'man', 'Man', 1),
    (1, 'vrouw', 'Vrouw', 2),
    (1, 'anders', 'Anders', 3),
    (3, 'alleen', 'Woon alleen', 1),
    (3, 'partner', 'Woon met partner', 2),
    (3, 'alleen_kinderen', 'Woon alleen met kind(-eren)', 3),
    (3, 'partner_kinderen', 'Woon met partner en kind(-eren)', 4),
    (3, 'ouders', 'Woon bij mijn (groot-) ouders', 5),
    (4, 'geen_probleem', 'Geen probleem', 1),
    (4, 'slecht_ter_been', 'Slecht ter been', 2),
    (4, 'rolstoel', 'Rolstoel afhankelijk', 3),
    (4, 'bedlegerig', 'Bedlegerig', 4),
    (5, 'geen', 'Geen eigen vervoer', 1),
    (5, 'rollator', 'Rollator', 2),
    (5, 'rolstoel', 'Rolstoel', 3),
    (5, 'scootmobiel', 'Scootmobiel', 4),
    (5, 'brommobiel', 'Brom mobiel', 5),
    (5, 'auto', 'Auto', 6),
    (6, 'ja', 'Ja', 1),
    (6, 'nee', 'Nee', 2),
    (7, 'familie', 'Steun van familie', 1),
    (7, 'vrienden', 'Steun van vrienden', 2),
    (7, 'buren', 'Steun van buren', 3),
    (7, 'geen', 'Heb geen steun in mijn omgeving', 4),
    (8, 'huur', 'Huurwoning', 1),
    (8, 'koop', 'Koopwoning', 2),
    (8, 'geen', 'Geen eigen woning. Te weten ...', 3);

INSERT INTO gids_role (name, description)
VALUES
    ('super_admin', 'Super admin'),
    ('admin', 'Admin'),
    ('user', 'User');

INSERT INTO gids_users (first_name, last_name, email, password, is_admin, is_verified, created_at)
VALUES (
    'Test', 'User', 'T1ester@gmail.com', '$2y$10$XNIi21Yqun198btQS/SEiuMPpALW/g0wZeE1q58y7ptBJwdjJD/8G', 1, 1, NOW()
);

INSERT INTO gids_user_role (user_id, role_id)
VALUES (
    (SELECT user_id FROM gids_users WHERE LOWER(email) = LOWER('T1ester@gmail.com') LIMIT 1),
    (SELECT id FROM gids_role WHERE name = 'super_admin' LIMIT 1)
);

SET FOREIGN_KEY_CHECKS = 1;
