# BostecWeb

BostecWeb ist eine modulare Webplattform für Behörden und Organisationen mit Sicherheitsaufgaben (BOS) in Deutschland. Die Plattform bietet verschiedene Module für die tägliche Arbeit von Führungseinheiten wie Einsatzleitungen oder Stabsstellen in einem Führungs- oder Leitungsstab.

## Funktionen

- **Modulares System**: Erweiterbare Plattform mit verschiedenen Funktionsmodulen
- **Benutzerverwaltung**: Verwaltung von Benutzern mit verschiedenen Rollen und Berechtigungen
- **Rollenverwaltung**: Flexible Zuweisung von Berechtigungen zu Rollen
- **Responsive Design**: Optimiert für die Darstellung auf Tablets (Android, iPad) und Desktop-PCs
- **Sicherheit**: Umfassende Sicherheitsmaßnahmen zum Schutz sensibler Daten

## Technologie-Stack

- **Backend**: PHP mit Laravel Framework
- **Datenbank**: MariaDB
- **Frontend**: HTML, CSS, JavaScript mit Bootstrap 5
- **Authentifizierung**: Laravel-eigenes Authentifizierungssystem

## Module

Die Plattform ist modular aufgebaut und kann je nach Bedarf erweitert werden. Folgende Module sind aktuell verfügbar oder geplant:

- **Dashboard**: Übersicht über alle verfügbaren Module und wichtige Informationen
- **Benutzerverwaltung**: Verwaltung von Benutzern, Rollen und Berechtigungen
- **Einsatztagebuch**: Dokumentation von Einsätzen und Ereignissen
- **Ressourcenmanagement**: Verwaltung von Personal und Material
- **Lagekarte**: Visualisierung der Einsatzlage auf einer interaktiven Karte
- **Aufgabenmanagement**: Zuweisung und Verfolgung von Aufgaben
- **Dokumentenverwaltung**: Speicherung und Verwaltung wichtiger Dokumente
- **Kommunikationsmodul**: Interne Kommunikation zwischen Einsatzkräften

## Installation

Detaillierte Installationsanweisungen finden Sie in der [INSTALLATION.md](INSTALLATION.md) Datei.

### Kurzanleitung

1. Repository klonen
2. Abhängigkeiten installieren: `composer install`
3. Umgebungsvariablen konfigurieren: `.env`
4. Datenbank migrieren: `php artisan migrate --seed`
5. Anwendung starten: `php artisan serve`

## Systemanforderungen

- PHP 8.1 oder höher
- MariaDB 10.5 oder höher
- Composer 2.x
- Webserver (Apache oder Nginx)

## Entwicklung

### Lokale Entwicklungsumgebung

Für die lokale Entwicklung können Sie Docker verwenden:

```bash
docker-compose up -d
```

### Testdaten

Die Anwendung wird mit Testdaten ausgeliefert, die über die Seeder eingespielt werden:

```bash
php artisan db:seed
```

### Standard-Anmeldedaten

- **Admin**:
  - E-Mail: admin@bostecweb.de
  - Passwort: admin123

- **Benutzer**:
  - E-Mail: user@bostecweb.de
  - Passwort: user123

**Wichtig:** Ändern Sie diese Passwörter in einer Produktivumgebung sofort!

## Beitragen

Wir freuen uns über Beiträge zur Verbesserung von BostecWeb. Bitte lesen Sie unsere Beitragsrichtlinien, bevor Sie einen Pull Request erstellen.

## Lizenz

BostecWeb ist unter der [MIT-Lizenz](LICENSE) lizenziert.

## Sicherheit

Wenn Sie Sicherheitslücken entdecken, senden Sie bitte eine E-Mail an sicherheit@bostecweb.de, anstatt ein öffentliches Issue zu erstellen.

## Kontakt

Bei Fragen oder Anregungen wenden Sie sich bitte an info@bostecweb.de.

## Screenshots

![Dashboard](docs/screenshots/dashboard.png)
![Benutzerverwaltung](docs/screenshots/users.png)
![Modulverwaltung](docs/screenshots/modules.png)

## Roadmap

- Integration weiterer Module für spezifische BOS-Anforderungen
- Verbesserung der Benutzeroberfläche und Benutzererfahrung
- Implementierung von API-Schnittstellen für externe Systeme
- Mehrsprachige Unterstützung
- Erweiterte Berichts- und Analysefunktionen
