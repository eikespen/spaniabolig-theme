# Spaniabolig WordPress Theme

Custom WordPress theme for spaniabolig.no — real estate site for Ciudad Quesada properties.

## Local Development

Requires [Docker Desktop](https://www.docker.com/products/docker-desktop/).

```bash
# Start WordPress locally at http://localhost:8080
docker compose up -d

# Stop
docker compose down

# Wipe everything (DB + uploads) and start fresh
docker compose down -v
```

### First-time setup
1. `docker compose up -d`
2. Open http://localhost:8080 and complete the WordPress installer
3. Go to **Appearance → Themes** and activate **Spaniabolig**
4. Go to **Settings → Permalinks** → save (flushes rewrite rules)
5. Add property types, urbanizations, and properties

The theme folder is mounted live — any edits you make here are instantly reflected in WordPress without restarting.

**phpMyAdmin:** http://localhost:8081

## Theme Structure

```
spaniabolig/
├── style.css                    ← Theme declaration
├── functions.php                ← CPT, taxonomies, AJAX search
├── front-page.php               ← Homepage
├── archive-property.php         ← Property listing with sidebar filter
├── single-property.php          ← Individual property + contact form
├── page-about.php               ← About page template
├── page.php                     ← Generic page
├── header.php / footer.php
├── template-parts/
│   └── property-card.php        ← Reusable property card
├── assets/
│   ├── css/main.css             ← All styles
│   ├── js/main.js               ← Nav toggle, gallery, map
│   └── img/logo-white.svg       ← Logo
└── docker-compose.yml           ← Local dev environment
```

## Custom Post Type: Properties

Each property has these fields (set via meta boxes in wp-admin):
- Price (€)
- Bedrooms / Bathrooms
- Size (m²)
- Status: For Sale / For Rent / Sold
- Build Type: Resale / New Build
- City/Area
- Reference #
- Lat/Lng (for map)
- Featured (shows on homepage)

## Taxonomies
- **Property Type**: villa, apartment, townhouse, etc.
- **Urbanization**: Ciudad Quesada Centro, Doña Pepa, La Marquesa Golf, etc.

## Deployment

Upload the theme folder to `/wp-content/themes/spaniabolig/` on spaniabolig.no, or use WP-CLI:

```bash
wp theme install /path/to/spaniabolig.zip --activate
```
