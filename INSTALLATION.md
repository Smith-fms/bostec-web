# Installationsanleitung für BostecWeb auf Debian

Diese Anleitung beschreibt die Installation von BostecWeb auf einem Debian-System. BostecWeb ist eine Plattform für Behörden und Organisationen mit Sicherheitsaufgaben (BOS), die verschiedene Module für die tägliche Arbeit bereitstellt.

## Systemvoraussetzungen

- Debian 11 (Bullseye) oder neuer
- Apache 2.4 oder Nginx 1.18+
- PHP 8.1 oder höher
- MariaDB 10.5 oder höher
- Composer 2.x
- Git

## 1. Systemaktualisierung

Aktualisieren Sie zunächst Ihr System:

```bash
sudo apt update
sudo apt upgrade -y
```

## 2. Erforderliche Pakete installieren

Installieren Sie die benötigten Pakete:

```bash
sudo apt install -y apache2 mariadb-server mariadb-client php php-cli php-fpm php-json php-common php-mysql php-zip php-gd php-mbstring php-curl php-xml php-pear php-bcmath php-intl php-readline git curl unzip
```

## 3. MariaDB konfigurieren

Sichern Sie die MariaDB-Installation:

```bash
sudo mysql_secure_installation
```

Folgen Sie den Anweisungen, um:
- Ein Root-Passwort festzulegen
- Anonyme Benutzer zu entfernen
- Root-Fernzugriff zu deaktivieren
- Die Testdatenbank zu entfernen
- Die Berechtigungstabellen neu zu laden

Erstellen Sie eine Datenbank und einen Benutzer für BostecWeb:

```bash
sudo mysql -u root -p
```

Führen Sie in der MariaDB-Konsole folgende Befehle aus:

```sql
CREATE DATABASE bostecweb CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'bostecweb'@'localhost' IDENTIFIED BY 'IhrSicheresPasswort';
GRANT ALL PRIVILEGES ON bostecweb.* TO 'bostecweb'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

Ersetzen Sie 'IhrSicheresPasswort' durch ein sicheres Passwort.

## 4. PHP konfigurieren

Bearbeiten Sie die PHP-Konfiguration:

```bash
sudo nano /etc/php/8.1/apache2/php.ini
# oder für PHP-FPM
# sudo nano /etc/php/8.1/fpm/php.ini
```

Ändern Sie folgende Werte:

```ini
memory_limit = 256M
upload_max_filesize = 64M
post_max_size = 64M
max_execution_time = 300
date.timezone = Europe/Berlin
```

## 5. Webserver konfigurieren

### Apache

Aktivieren Sie benötigte Module:

```bash
sudo a2enmod rewrite
sudo a2enmod ssl
sudo systemctl restart apache2
```

Erstellen Sie eine virtuelle Host-Konfiguration:

```bash
sudo nano /etc/apache2/sites-available/bostecweb.conf
```

Fügen Sie folgende Konfiguration ein:

```apache
<VirtualHost *:80>
    ServerName bostecweb.example.com
    ServerAdmin webmaster@example.com
    DocumentRoot /var/www/bostecweb/public

    <Directory /var/www/bostecweb/public>
        Options -Indexes +FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>

    ErrorLog ${APACHE_LOG_DIR}/bostecweb_error.log
    CustomLog ${APACHE_LOG_DIR}/bostecweb_access.log combined
</VirtualHost>
```

Aktivieren Sie die Konfiguration:

```bash
sudo a2ensite bostecweb.conf
sudo systemctl reload apache2
```

### Nginx

Erstellen Sie eine Nginx-Konfiguration:

```bash
sudo nano /etc/nginx/sites-available/bostecweb
```

Fügen Sie folgende Konfiguration ein:

```nginx
server {
    listen 80;
    server_name bostecweb.example.com;
    root /var/www/bostecweb/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

Aktivieren Sie die Konfiguration:

```bash
sudo ln -s /etc/nginx/sites-available/bostecweb /etc/nginx/sites-enabled/
sudo systemctl reload nginx
```

## 6. Composer installieren

```bash
curl -sS https://getcomposer.org/installer | sudo php -- --install-dir=/usr/local/bin --filename=composer
```

## 7. BostecWeb herunterladen

```bash
cd /var/www
sudo git clone https://github.com/ihre-organisation/bostecweb.git
cd bostecweb
```

## 8. Berechtigungen einrichten

```bash
sudo chown -R www-data:www-data /var/www/bostecweb
sudo find /var/www/bostecweb -type f -exec chmod 644 {} \;
sudo find /var/www/bostecweb -type d -exec chmod 755 {} \;
sudo chmod -R 775 /var/www/bostecweb/storage
sudo chmod -R 775 /var/www/bostecweb/bootstrap/cache
```

## 9. Abhängigkeiten installieren

```bash
cd /var/www/bostecweb
sudo -u www-data composer install --no-dev --optimize-autoloader
```

## 10. Umgebungskonfiguration

```bash
sudo -u www-data cp .env.example .env
sudo -u www-data php artisan key:generate
```

Bearbeiten Sie die .env-Datei:

```bash
sudo nano .env
```

Passen Sie folgende Werte an:

```
APP_NAME=BostecWeb
APP_ENV=production
APP_DEBUG=false
APP_URL=http://bostecweb.example.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=bostecweb
DB_USERNAME=bostecweb
DB_PASSWORD=IhrSicheresPasswort
```

## 11. Datenbank migrieren und Seed-Daten einspielen

```bash
cd /var/www/bostecweb
sudo -u www-data php artisan migrate --seed
```

## 12. Anwendungscache erstellen

```bash
sudo -u www-data php artisan config:cache
sudo -u www-data php artisan route:cache
sudo -u www-data php artisan view:cache
```

## 13. Symbolischen Link für den Speicher erstellen

```bash
sudo -u www-data php artisan storage:link
```

## 14. Cron-Job für geplante Aufgaben einrichten

Bearbeiten Sie die Crontab für den www-data-Benutzer:

```bash
sudo crontab -u www-data -e
```

Fügen Sie folgende Zeile hinzu:

```
* * * * * cd /var/www/bostecweb && php artisan schedule:run >> /dev/null 2>&1
```

## 15. Firewall konfigurieren

Wenn Sie UFW verwenden:

```bash
sudo ufw allow 80/tcp
sudo ufw allow 443/tcp
```

## 16. SSL-Zertifikat einrichten (optional, aber empfohlen)

Mit Let's Encrypt und Certbot:

```bash
sudo apt install -y certbot python3-certbot-apache
# oder für Nginx
# sudo apt install -y certbot python3-certbot-nginx

sudo certbot --apache -d bostecweb.example.com
# oder für Nginx
# sudo certbot --nginx -d bostecweb.example.com
```

## 17. Anwendung testen

Öffnen Sie einen Webbrowser und navigieren Sie zu Ihrer Domain (http://bostecweb.example.com oder https://bostecweb.example.com, wenn SSL aktiviert ist).

## 18. Standard-Anmeldedaten

Nach der Installation können Sie sich mit folgenden Anmeldedaten anmelden:

- E-Mail: admin@bostecweb.de
- Passwort: admin123

**Wichtig:** Ändern Sie das Passwort sofort nach der ersten Anmeldung!

## Fehlerbehebung

### Berechtigungsprobleme

Wenn Sie auf Berechtigungsprobleme stoßen, führen Sie folgende Befehle aus:

```bash
sudo chown -R www-data:www-data /var/www/bostecweb
sudo chmod -R 775 /var/www/bostecweb/storage
sudo chmod -R 775 /var/www/bostecweb/bootstrap/cache
```

### Datenbank-Verbindungsprobleme

Überprüfen Sie die Datenbankverbindung:

```bash
cd /var/www/bostecweb
sudo -u www-data php artisan db:monitor
```

### Webserver-Logs überprüfen

Für Apache:

```bash
sudo tail -f /var/log/apache2/bostecweb_error.log
```

Für Nginx:

```bash
sudo tail -f /var/log/nginx/error.log
```

### Laravel-Logs überprüfen

```bash
sudo tail -f /var/www/bostecweb/storage/logs/laravel.log
```

## Aktualisierung

Um BostecWeb zu aktualisieren:

```bash
cd /var/www/bostecweb
sudo git pull
sudo -u www-data composer install --no-dev --optimize-autoloader
sudo -u www-data php artisan migrate
sudo -u www-data php artisan config:cache
sudo -u www-data php artisan route:cache
sudo -u www-data php artisan view:cache
```

## Sicherheitshinweise

- Ändern Sie sofort das Standard-Admin-Passwort
- Halten Sie das System und alle Pakete aktuell
- Verwenden Sie HTTPS mit einem gültigen SSL-Zertifikat
- Beschränken Sie den SSH-Zugriff auf vertrauenswürdige IPs
- Richten Sie regelmäßige Backups ein
- Überwachen Sie die Logs auf verdächtige Aktivitäten
