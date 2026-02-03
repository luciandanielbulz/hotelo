# SEO-Optimierung – Venditio / quickBill

Diese Anwendung ist für Suchmaschinen optimiert. Übersicht der Maßnahmen und Konfiguration.

---

## Bereits umgesetzt

### Meta-Tags & Open Graph
- **Title, Description, Keywords** pro Seite (über `$seoData` und Komponente `<x-seo-meta>`)
- **Canonical URL** zur Vermeidung von Duplicate Content
- **Open Graph** (og:title, og:description, og:image, og:url) für Social Sharing
- **Twitter Cards** (summary_large_image)
- **Robots** (index/follow, max-snippet, max-image-preview)
- **Sprache** (language, og:locale)

### Strukturierte Daten (JSON-LD)
- **Organization** (Firmenname, Logo, URL)
- **WebSite** (mit Suchaktion, falls Suchfunktion)
- **SoftwareApplication** (App-Beschreibung, Kategorie)
- **FAQPage** (auf Welcome/Features wo genutzt)
- **BreadcrumbList** (über SeoHelper verfügbar)

### Sitemap & Crawling
- **Sitemap:** `/sitemap.xml` – enthält Startseite, Login, Register, Über uns, Preise, Kontakt, Impressum, Datenschutz, Cookies, alle Feature-Seiten
- **robots.txt:** dynamisch unter `/robots.txt` – verweist auf Sitemap und schließt geschützte Bereiche (Dashboard, Rechnungen, Angebote, etc.) von der Indexierung aus

### Technik
- **SeoHelper** (`app/Helpers/SeoHelper.php`) – zentrale Funktionen für Meta-Tags und strukturierte Daten
- **Config** `config/seo.php` – Standard-Titel, -Beschreibung, -Bild, Sitemap/Robots-Optionen
- **Komponente** `resources/views/components/seo-meta.blade.php` – einheitliche Ausgabe aller Meta- und JSON-LD-Tags

---

## Konfiguration (.env)

| Variable | Bedeutung | Beispiel |
|----------|-----------|----------|
| `SEO_DEFAULT_TITLE` | Standard-Seitentitel | quickBill - Rechnungsverwaltung |
| `SEO_DEFAULT_DESCRIPTION` | Standard-Meta-Description | Kurzbeschreibung der App |
| `SEO_DEFAULT_KEYWORDS` | Standard-Keywords (kommagetrennt) | Rechnungssoftware, quickBill |
| `SEO_DEFAULT_IMAGE` | Absoluter Pfad oder relativer Pfad zum Standard-Bild (og:image) | logo/quickBill-Logo-alone.png |
| `SEO_DEFAULT_AUTHOR` | Autor für meta author | Bulz |
| `SEO_TWITTER_HANDLE` | Twitter-Handle für Karten | @quickBill |
| `SEO_FACEBOOK_APP_ID` | Optional: Facebook App ID | |
| `SEO_SITEMAP_ENABLED` | Sitemap aktiv (true/false) | true |
| `SEO_SITEMAP_CACHE_DURATION` | Sitemap-Cache in Sekunden | 3600 |
| `SEO_ROBOTS_ALLOW` | Crawling erlauben (true) oder verbieten (false) | true |
| `SEO_CANONICAL_ENABLED` | Canonical-URL ausgeben | true |
| `SEO_OG_ENABLED` | Open-Graph-Tags ausgeben | true |
| `SEO_TWITTER_CARD_ENABLED` | Twitter-Card-Tags ausgeben | true |

Wichtig: **APP_URL** muss in der Produktion korrekt gesetzt sein (https://ihre-domain.de), damit Canonical, og:url und Sitemap-URLs stimmen.

---

## robots.txt – statisch vs. dynamisch

- **Dynamische robots.txt:** Route `GET /robots.txt` liefert die Version aus `resources/views/seo/robots.blade.php` inkl. Sitemap-Hinweis und aller in `config/seo.php` hinterlegten `robots_disallow_paths`.
- **Statische Datei:** Falls im Webserver eine Datei `public/robots.txt` existiert, wird diese oft zuerst ausgeliefert. Dann wird die dynamische Version nicht genutzt.
- **Empfehlung:** Für die volle Steuerung (Disallow-Pfade aus der Config) entweder:
  - `public/robots.txt` entfernen und den Webserver so konfigurieren, dass Anfragen zu `/robots.txt` an Laravel (index.php) gehen, **oder**
  - `public/robots.txt` nur mit dem Sitemap-Hinweis belassen und Disallow-Regeln weglassen, wenn Sie die dynamische Route nutzen wollen.

---

## Checkliste für neue öffentliche Seiten

1. **SeoData übergeben**  
   Pro Seite eindeutigen Titel und Description setzen, z. B.:
   ```php
   $seoData = [
       'title' => 'Seitentitel',
       'description' => 'Kurze Beschreibung (ca. 150–160 Zeichen).',
       'keywords' => 'optional, kommagetrennt',
   ];
   ```
2. **Layout/Blade**  
   `<x-seo-meta :seoData="$seoData" :canonicalUrl="url()->current()" :structuredData="$structuredData" />` einbinden (wie auf Welcome, About, Pricing, Contact, Features, Legal).
3. **Sitemap**  
   Neue öffentliche URL in `SeoController::sitemap()` in das Array `$publicPages` aufnehmen (Route-Name oder URL, changefreq, priority).
4. **Canonical**  
   Bei mehrsprachigen oder mehreren URLs zur gleichen Seite: `canonicalUrl` auf die gewünschte kanonische URL setzen.

---

## Weitere Tipps

- **Titel:** Pro Seite einmalig, erkennbar (z. B. „Preise – quickBill“), Länge ca. 50–60 Zeichen.
- **Description:** Pro Seite einmalig, aussagekräftig, ca. 150–160 Zeichen; wird oft als Snippet in den Suchergebnissen genutzt.
- **Bilder:** Wo es passt, `alt`-Texte setzen; Standard-Bild für og:image in `config/seo.php` bzw. `SEO_DEFAULT_IMAGE` verwenden und als absoluten URL ausliefern (SeoHelper macht das bereits, wenn nötig).
- **Performance:** Ladezeiten und mobile Darstellung beeinflussen das Ranking; Sitemap-Cache (`SEO_SITEMAP_CACHE_DURATION`) reduziert Last.
- **Google Search Console:** Nach Go-Live die Property anlegen, Sitemap-URL (`https://ihre-domain.de/sitemap.xml`) einreichen und Indexierung prüfen.

---

## Dateien im Projekt

| Datei | Zweck |
|-------|--------|
| `app/Helpers/SeoHelper.php` | Meta-Tags, strukturierte Daten (Organization, WebSite, FAQ, Breadcrumb, etc.) |
| `app/Http/Controllers/SeoController.php` | Auslieferung von Sitemap und robots.txt |
| `config/seo.php` | Standardwerte und Schalter für SEO-Features |
| `resources/views/components/seo-meta.blade.php` | Ausgabe Meta, Canonical, OG, Twitter, JSON-LD |
| `resources/views/seo/sitemap.blade.php` | XML-Sitemap-View |
| `resources/views/seo/robots.blade.php` | robots.txt-View |
