-- Opprett database
CREATE DATABASE IF NOT EXISTS CRM_GR4;
USE CRM_GR4;
 
-- KUNDE-tabell
CREATE TABLE kunde (
    kunde_id INT AUTO_INCREMENT PRIMARY KEY,
    firmanavn VARCHAR(100) NOT NULL,
    org_nr VARCHAR(9) NOT NULL UNIQUE,
    adresse VARCHAR(150),
    postnr VARCHAR(4),
    poststed VARCHAR(50),
    epost VARCHAR(100),
    telefon VARCHAR(15),
    opprettet_dato DATETIME DEFAULT CURRENT_TIMESTAMP,
    status ENUM('Aktiv', 'Inaktiv') DEFAULT 'Aktiv'
);
 
-- KONTAKTPERSON-tabell
CREATE TABLE kontaktperson (
    kontakt_id INT AUTO_INCREMENT PRIMARY KEY,
    kunde_id INT NOT NULL,
    fornavn VARCHAR(50) NOT NULL,
    etternavn VARCHAR(50) NOT NULL,
    stilling VARCHAR(100),
    avdeling VARCHAR(50),
    epost VARCHAR(100),
    telefon VARCHAR(15),
    mobil VARCHAR(15),
    opprettet_dato DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (kunde_id) REFERENCES kunde(kunde_id)
    ON DELETE CASCADE
    ON UPDATE CASCADE
);

-- Brukere
CREATE TABLE IF NOT EXISTS users (
    user_id    INT AUTO_INCREMENT PRIMARY KEY,
    fornavn    VARCHAR(50)  NOT NULL,
    etternavn  VARCHAR(50)  NOT NULL,
    username   VARCHAR(50)  NOT NULL UNIQUE,
    password   VARCHAR(255) NOT NULL,
    opprettet  TIMESTAMP    DEFAULT CURRENT_TIMESTAMP
);

-- INSERT INTO eksempler 

-- KUNDER 
INSERT INTO kunde (firmanavn, org_nr, adresse, postnr, poststed, epost, telefon, status) VALUES
('Norsk Teknologi AS',       '123456789', 'Storgata 1',        '0182', 'Oslo',       'kontakt@norsktek.no',     '22001100', 'Aktiv'),
('Bergen Bygg AS',           '234567891', 'Bryggen 5',         '5003', 'Bergen',     'post@bergenbygg.no',      '55001100', 'Aktiv'),
('Trondheim Handel AS',      '345678912', 'Munkegata 12',      '7011', 'Trondheim',  'info@thdhandel.no',       '73001100', 'Aktiv'),
('Stavanger Energi AS',      '456789123', 'Oljeveien 3',       '4005', 'Stavanger',  'post@stavenergi.no',      '51001100', 'Inaktiv'),
('Kristiansand Logistikk AS','567891234', 'Havnegata 8',       '4610', 'Kristiansand','info@kslogistikk.no',    '38001100', 'Aktiv'),
('Tromsø Fisk AS',           '678912345', 'Strandveien 22',    '9008', 'Tromsø',     'kontakt@tromsfisk.no',    '77001100', 'Aktiv'),
('Drammen Industri AS',      '789123456', 'Industriveien 7',   '3015', 'Drammen',    'post@dramindustri.no',    '32001100', 'Inaktiv'),
('Fredrikstad Mekaniske AS', '891234567', 'Verkstedgata 4',    '1606', 'Fredrikstad', 'info@fredmek.no',        '69001100', 'Aktiv'),
('Bodø Maritim AS',          '912345678', 'Sjøgata 16',        '8004', 'Bodø',       'kontakt@bodmaritim.no',   '75001100', 'Aktiv'),
('Ålesund Shipping AS',      '101234567', 'Kaipromenaden 9',   '6002', 'Ålesund',    'post@alesundship.no',     '70001100', 'Aktiv');
 
-- KONTAKTPERSONER (én per kunde, kunde_id 1–10)
INSERT INTO kontaktperson (kunde_id, fornavn, etternavn, stilling, avdeling, epost, telefon, mobil) VALUES
(1,  'Lars',    'Hansen',    'Daglig leder',    'Ledelse',    'lars.hansen@norsktek.no',      '22001101', '91001101'),
(2,  'Kari',    'Olsen',     'Prosjektleder',   'Prosjekt',   'kari.olsen@bergenbygg.no',     '55001102', '92001102'),
(3,  'Per',     'Johansen',  'Økonomisjef',     'Økonomi',    'per.johansen@thdhandel.no',    '73001103', '93001103'),
(4,  'Anne',    'Andersen',  'Salgssjef',       'Salg',       'anne.andersen@stavenergi.no',  '51001104', '94001104'),
(5,  'Ola',     'Nilsen',    'Logistikksjef',   'Logistikk',  'ola.nilsen@kslogistikk.no',    '38001105', '95001105'),
(6,  'Ingrid',  'Berg',      'Kvalitetssjef',   'Kvalitet',   'ingrid.berg@tromsfisk.no',     '77001106', '96001106'),
(7,  'Tor',     'Larsen',    'IT-ansvarlig',    'IT',         'tor.larsen@dramindustri.no',   '32001107', '97001107'),
(8,  'Hanne',   'Moen',      'HR-sjef',         'HR',         'hanne.moen@fredmek.no',        '69001108', '98001108'),
(9,  'Erik',    'Kristiansen','Driftssjef',     'Drift',      'erik.k@bodmaritim.no',         '75001109', '99001109'),
(10, 'Silje',   'Dahl',      'Markedssjef',     'Marked',     'silje.dahl@alesundship.no',    '70001110', '90001110');