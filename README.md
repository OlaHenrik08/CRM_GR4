# CRM Gruppe 4

Et enkelt CRM-system (Customer Relationship Management) utviklet i PHP og MySQL. Systemet lar innloggede brukere administrere kunder og tilhørende kontaktpersoner.

---

## Innhold

- [Funksjonalitet](#funksjonalitet)
- [Teknologi](#teknologi)
- [Filstruktur](#filstruktur)
- [Databasestruktur](#databasestruktur)
- [Installasjon og oppsett](#installasjon-og-oppsett)
- [Bruk](#bruk)
- [Sikkerhet](#sikkerhet)

---

## Funksjonalitet

### Autentisering
- Registrering av ny bruker med fornavn, etternavn, brukernavn og passord
- Innlogging med brukernavn og passord
- Utlogging
- Alle sider (utenom login og registrering) krever aktiv innlogging

### Kunder
- Oversikt over alle kunder i en søkbar tabell
- Legg til ny kunde
- Rediger eksisterende kunde
- Slett kunde (sletter også tilhørende kontaktpersoner automatisk via CASCADE)
- Detaljvisning per kunde med tilhørende kontaktpersoner
- Status på kunde: **Aktiv** eller **Inaktiv**
- Live-søk og serversøk på firmanavn, org.nr og e-post

### Kontaktpersoner
- Oversikt over alle kontaktpersoner på tvers av kunder
- Legg til ny kontaktperson (kan knyttes til en spesifikk kunde)
- Rediger eksisterende kontaktperson
- Slett kontaktperson
- Søk på navn, e-post og firmanavn

### Dashboard-statistikk
Et lite stats-panel vises automatisk over tabellene og viser:
- Totalt antall kunder / kontaktpersoner
- Antall aktive og inaktive kunder
- Antall unike firmaer (på kontaktpersoner-siden)

---

## Teknologi

| Komponent | Teknologi |
|-----------|-----------|
| Backend | PHP (prosedyrebasert med MySQLi) |
| Database | MySQL |
| Frontend | HTML, CSS, JavaScript (vanilla) |
| Autentisering | PHP Sessions + `password_hash` / `password_verify` |
| Sikkerhet | Prepared statements, `htmlspecialchars()` (XSS-beskyttelse) |

---

## Filstruktur

```
/
├── index.php              # Kundeoversikt (forsiden)
├── kunde_skjema.php       # Skjema for å legge til / redigere kunde
├── kunde_detalj.php       # Detaljside for én kunde med kontaktpersoner
├── slett_kunde.php        # Håndterer sletting av kunde
├── kontaktpersoner.php    # Oversikt over alle kontaktpersoner
├── kontakt_skjema.php     # Skjema for å legge til / redigere kontaktperson
├── slett_kontakt.php      # Håndterer sletting av kontaktperson
├── login.php              # Innloggingsside
├── registrer.php          # Registreringsside for ny bruker
├── logout.php             # Logger ut og ødelegger session
├── db.php                 # Databasetilkobling og h()-hjelpefunksjon
├── assets/
│   ├── style.css          # Stylesheet (moderne, responsivt design)
│   └── script.js          # Live-søk og statistikkpanel
└── CRM_GR4.sql            # SQL-skript for å opprette og populere databasen
```

---

## Databasestruktur

Databasen heter `CRM_GR4` og består av tre tabeller:

### `kunde`
| Kolonne | Type | Beskrivelse |
|---------|------|-------------|
| `kunde_id` | INT (PK, AUTO) | Unik ID |
| `firmanavn` | VARCHAR(100) | Navn på bedriften |
| `org_nr` | VARCHAR(9) | Organisasjonsnummer (unikt) |
| `adresse` | VARCHAR(150) | Gateadresse |
| `postnr` | VARCHAR(4) | Postnummer |
| `poststed` | VARCHAR(50) | Poststed |
| `epost` | VARCHAR(100) | E-postadresse |
| `telefon` | VARCHAR(15) | Telefonnummer |
| `opprettet_dato` | DATETIME | Opprettet automatisk |
| `status` | ENUM | `Aktiv` eller `Inaktiv` |

### `kontaktperson`
| Kolonne | Type | Beskrivelse |
|---------|------|-------------|
| `kontakt_id` | INT (PK, AUTO) | Unik ID |
| `kunde_id` | INT (FK) | Referanse til `kunde` |
| `fornavn` | VARCHAR(50) | Fornavn |
| `etternavn` | VARCHAR(50) | Etternavn |
| `stilling` | VARCHAR(100) | Stillingstittel |
| `avdeling` | VARCHAR(50) | Avdeling |
| `epost` | VARCHAR(100) | E-postadresse |
| `telefon` | VARCHAR(15) | Telefonnummer |
| `mobil` | VARCHAR(15) | Mobilnummer |
| `opprettet_dato` | DATETIME | Opprettet automatisk |

> Kontaktpersoner er knyttet til kunder via `kunde_id`. Ved sletting av en kunde vil alle tilknyttede kontaktpersoner slettes automatisk (`ON DELETE CASCADE`).

### `users`
| Kolonne | Type | Beskrivelse |
|---------|------|-------------|
| `user_id` | INT (PK, AUTO) | Unik ID |
| `fornavn` | VARCHAR(50) | Fornavn |
| `etternavn` | VARCHAR(50) | Etternavn |
| `username` | VARCHAR(50) | Brukernavn (unikt) |
| `password` | VARCHAR(255) | Bcrypt-hashet passord |
| `opprettet` | TIMESTAMP | Opprettet automatisk |

---

## Installasjon og oppsett

### Krav
- PHP 7.4 eller nyere
- MySQL 5.7 eller nyere
- En webserver (f.eks. Apache via XAMPP / WAMP / MAMP, eller en live PHP-host)

### Steg 1: Klon eller last ned prosjektet

Legg filene i webserverens rot (f.eks. `htdocs/` i XAMPP).

### Steg 2: Opprett databasen

Kjør `CRM_GR4.sql` i MySQL for å opprette tabeller og legge inn testdata:

```bash
mysql -u root -p < CRM_GR4.sql
```

Eller importer filen via phpMyAdmin.

### Steg 3: Konfigurer databasetilkobling

Åpne `db.php` og oppdater tilkoblingsvariablene:

```php
$servername = "localhost";
$username   = "ditt_brukernavn";
$password   = "ditt_passord";
$db         = "CRM_GR4";
```

### Steg 4: Start applikasjonen

Åpne nettleseren og gå til:

```
http://localhost/[prosjektmappe]/login.php
```

---

## Bruk

1. **Registrer en bruker** via `registrer.php` eller logg inn hvis du allerede har en bruker.
2. **Kundeadministrasjon:** Fra forsiden (`index.php`) kan du se alle kunder, søke, legge til, redigere eller slette.
3. **Kundedetaljer:** Klikk «Vis» på en kunde for å se detaljer og administrere kundens kontaktpersoner.
4. **Kontaktpersoner:** Via menyen kan du se alle kontaktpersoner på tvers av kunder, og legge til eller redigere dem.
5. **Logg ut** via «Logg ut» i sidemenyen.

---

## Sikkerhet

- **Passord** lagres aldri i klartekst — bcrypt brukes via `password_hash()` og verifiseres med `password_verify()`.
- **SQL-injection** forebygges med prepared statements (`mysqli_prepare`) i alle kritiske spørringer.
- **XSS-angrep** forebygges med `htmlspecialchars()`, tilgjengelig som hjelpefunksjonen `h()` definert i `db.php`.
- **Sesjonsbeskyttelse:** Alle beskyttede sider sjekker `$_SESSION['user']` og redirecter til login ved manglende tilgang.

> **Merk:** Noen eldre deler av koden (f.eks. i `kontakt_skjema.php`) bruker direkte strenginterpolasjon i SQL-spørringer. Dette bør oppdateres til prepared statements for full beskyttelse.