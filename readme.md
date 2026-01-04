# GlowCare Webshop

GlowCare is een online **PHP‑webshop** voor verzorgingsproducten met een MySQL‑database.  
De site ondersteunt productoverzicht, detailpagina’s, winkelwagen, varianten en gebruikerslogins.

---

## 1. Projectoverzicht

Dit project is gemaakt als opdracht voor het vak Back-end development.  
Doel: een volledige online store bouwen met:

- productbeheer door een admin
- een winkelmandje en bestelproces
- gebruikersaccounts (registreren/inloggen)
- basisbeveiliging tegen XSS en SQL‑injectie

---

## 2. Online demo

- **URL:** `https://webshop-glowcare.infinityfreeapp.com/`
- **Hosting:** InfinityFree (gratis PHP + MySQL hosting)
- **Aanbevolen browser:** laatste versie van Chrome of Edge

---

## 3. Inloggegevens (test)

### Gebruiker

- Gebruikersnaam / e‑mail: `yasmin@user.com`
- Wachtwoord: `User`

### Admin

- Gebruikersnaam / e‑mail: `yasmin@admin.com`
- Wachtwoord: `Admin`

---

## 4. Functionaliteiten

### Publieke functies

- **Home (home.php)**

  - Overzicht van alle producten.
  - Per product: afbeelding, naam, merk, prijs en korte omschrijving.

- **Productdetail (detailpagina.php)**

  - Detailinformatie van één product.
  - Keuze van varianten (bijvoorbeeld formaat) met aangepaste prijs. (alleen van product_id = 1)
  - Knop om het product (met gekozen variant en aantal) aan de winkelwagen toe te voegen.

- **Winkelwagen (winkelwagen.php)**

  - Overzicht van de producten in de sessie‑winkelwagen.
  - Subtotaal per product en totaalbedrag van de volledige bestelling.
  - Mogelijkheid om items te verwijderen.
  - Link naar afrekenpagina (checkout.php).

- **Afrekenen (checkout.php)**
  - Simpele checkoutflow (zonder echte betaalprovider).
  - Controle op minimale gegevens (bv. naam, adres).

### Gebruikers & authenticatie

- **Registreren (register.php)**

  - Nieuwe gebruikers kunnen een account aanmaken.
  - Wachtwoorden worden gehasht opgeslagen.

- **Inloggen (login.php)**

  - Inloggen met e‑mail en wachtwoord.
  - Sessies worden gebruikt om de ingelogde status te bewaren.

- **Uitloggen (logout.php)**
  - Vernietigt de sessie en stuurt de gebruiker terug naar de homepage.

### Comments

- **Reviews bij producten**
  - Ingelogde gebruikers kunnen een comment achterlaten mits product aangekocht.
  - Reviews worden gekoppeld aan het juiste product en de juiste gebruiker.

### Adminfunctionaliteit

- **Productbeheer**

  - Admin kan producten toevoegen, bewerken en verwijderen.
  - Bij het beheren van een product kunnen merk, categorie, type, prijs, beschrijving en afbeelding ingesteld worden.

---

## 5. Technische details

### Technologieën

- PHP 8.x
- MySQL (PDO, prepared statements)
- HTML5 & CSS3

### Structuur (belangrijkste bestanden)

- `home.php` – startpagina met productoverzicht
- `detailpagina.php` – detailpagina per product
- `winkelwagen.php` – winkelwagenoverzicht
- `checkout.php` – afrekenpagina
- `login.php` – loginformulier
- `registrer.php` – registratieformulier
- `logout.php` – uitloggen
- `nav.inc.php` – navigatie
- `index.php` – redirect naar `home.php`

**Back‑end / classes**

- `classes/Database.php` – PDO‑connectie met de MySQL‑database
- `classes/Product.php` – productlogica (ophalen producten, varianten, filters)
- `classes/Cart.php` – winkelwagenlogica (sessie, toevoegen/verwijderen, totaal)
- `classes/Person.php` – basisclass voor personen
- `classes/User.php` – gebruikersclass (login, registratie, wachtwoord wijzigen)
- `classes/Admin.php` – adminsclass (login)
- `traits/Password.php` – trait voor password hashing en verificatie
- `traits/passwordTrait.php` – trait voor te controleren of een ingegeven wachtwoord overeenkomt

**Overig**

- `style2.css`, `admin.css` – opmaak van de site
- `images/` – productafbeeldingen en lay‑out afbeeldingen

---

## 6. Database

De database draait op de MySQL‑server van de hostingprovider.  
De connectie gebeurt via `classes/Database.php` met PDO en prepared statements.

### Belangrijkste tabellen

- `products` – producten (id, naam, beschrijving, prijs, afbeelding, merk_id, category_id, …)
- `brands` – merken
- `categories` – categorieën
- `product_variants` – varianten per product (grootte, extra prijs)
- `users` – gebruikers (naam, e‑mail, gehasht wachtwoord, rol)
- `comments` – comments per product en user
- `orders` – bestellingen (id, user_id, datum, totaalbedrag)
- `product_ordered` – producten per bestelling

---

## 7. Installatie (lokaal)

1. Clone of download dit project.
2. Plaats alle bestanden in de documentroot van je lokale server (bv. `htdocs/webshop`).
3. Maak in MySQL een database aan (bijv. `webshop`).
4. Importeer het SQL‑bestand (`webshop.sql`).
5. Pas `classes/Database.php` aan naar de lokale instellingen (host, db‑naam, user, password).
6. Start Apache en MySQL en surf naar `http://localhost/webshop/home.php`.

---

## 8. Deploy (hoe dit project online staat)

1. Nieuwe MySQL‑database aangemaakt op InfinityFree.
2. Lokale database geëxporteerd en geïmporteerd via phpMyAdmin van de host.
3. In `classes/Database.php` de hostgegevens van InfinityFree ingevuld  
   (`sqlXXX.infinityfree.com`, database‑naam, user en wachtwoord).
4. Alle projectbestanden geüpload naar `htdocs/webshop`.
5. Een `index.php` in `htdocs` toegevoegd die doorstuurt naar `webshop/home.php`.

---

## 9. Beveiliging

- Wachtwoorden worden gehasht opgeslagen via PHP’s password‑hashing functies.
- Alle database‑queries gebruiken prepared statements om **SQL‑injectie** te voorkomen.
- Gebruikersinput wordt vóór output ge‑escaped (bijv. via `htmlspecialchars`) om **XSS** te beperken.
- Sessies worden gebruikt voor login en winkelwagen; gevoelige gegevens staan niet in de URL’s.

---

## 10. Checklist ONLINE STORE

- [x] Admin kan producten toevoegen, bewerken én verwijderen
- [x] Gebruiker kan een aankoop doen via winkelmandje
- [x] Gebruiker kan een comment plaatsen
- [x] Gebruiker kan producten filteren per categorie
- [x] Gebruiker kan wachtwoord wijzigen
- [x] Gebruiker kan bestellingen bekijken
- [x] Noodzakelijke controles werken (prijs > 0, voldoende cash, …)
- [x] Het project staat online en werkt
- [x] XSS-aanvallen zijn voorkomen
- [x] SQL-injectie-aanvallen zijn voorkomen
- [x] GIT commit messages voldoen aan conventies
- [x] Correct gebruik van OOP door middel van meerdere klassen
- [x] In de frontend is nergens een spoor van SQL terug te vinden, enkel in de klasses

---

## 11. Hoe testen

1. Ga naar de online URL.
2. Bekijk de homepage en open een productdetail.
3. Voeg een product (met variant en aantal) toe aan de winkelwagen.
4. Open de winkelwagen, controleer subtotaal en totaal, verwijder een item.
5. Registreer een nieuwe gebruiker en log in / uit.
6. Plaats een review bij een product.
7. Log in als admin en voeg een nieuw product toe, bewerk en verwijder het.

---

**Auteur:** Yasmin  
**Versie:** 1.0 — 2025-2026  
**Licentie:** Voor educatief gebruik
