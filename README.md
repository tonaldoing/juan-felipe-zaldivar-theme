# Tema WordPress: Juan Felipe Zaldívar

Tema a medida para el blog de Juan Felipe Zaldívar: poemas propios, traducciones, lecturas de libros, notas de cine y eventos. Texto primero, sin ornamentos, sin JavaScript en el front.

El manual para el autor (cómo publicar, cómo se ven los tipos de texto, cómo borrar el contenido de ejemplo) está en [docs/MANUAL.md](docs/MANUAL.md).

## Cómo está armado

- **Tema clásico** (templates PHP + Sass). El autor escribe en el editor de bloques; el tema decide cómo se muestra cada cosa.
- **Una taxonomía `tipo`**, obligatoria y única por entrada: Poema, Traducción, Libros, Cine, Nota. Define el layout de la entrada y qué campos extra aparecen en el editor. Si una entrada se guarda sin tipo, queda como Nota.
- **Tipo de contenido `evento`** con fecha, hora, lugar, ciudad y link. El próximo evento aparece destacado en la portada y desaparece solo cuando pasa la fecha. Archivo en `/eventos/` con próximos y pasados.
- **Página Índice** (template "Índice"): lista automática de todos los textos agrupados por tipo.
- **Newsletter**: caja de suscripción en el pie que envía el email a un proveedor externo (Buttondown, Mailchimp, etc.). Se configura en Apariencia → Opciones del sitio. Si no hay URL cargada, no se muestra.
- **Sin comentarios**, sin archivos de autor, sin XML-RPC, sin enumeración de usuarios.
- **Feeds RSS** general y por tipo (`/tipo/poema/feed/`, etc.).
- **Modo oscuro** automático según el sistema.

### Estructura

```
functions.php            carga inc/
inc/
  setup.php              soportes, menús, sin comentarios, menú de respaldo
  taxonomies.php         taxonomía tipo + términos base
  post-types.php         CPT evento + columnas del admin
  meta.php               meta boxes: tipo (radios), campos por tipo, datos del evento
  queries.php            portada, archivo de eventos, búsqueda, próximos eventos
  theme-options.php      Apariencia → Opciones del sitio (newsletter, contacto, textos)
  seo.php                meta description, Open Graph, feeds por tipo
  security.php           endurecimiento básico
  demo-content.php       aviso + botón para borrar el contenido de ejemplo
  helpers.php            funciones usadas por los templates
index.php                portada y archivos (flujo cronológico + filtro por tipo)
single.php               entrada; el layout cambia según el tipo
single-evento.php / archive-evento.php
page.php / page-templates/indice.php
search.php / 404.php
template-parts/          card de entrada, sub-encabezados por tipo, evento, newsletter, filtro de tipos
assets/scss/             fuentes de estilos; se compilan a assets/css/
assets/fonts/            Literata (OFL), variable
demo-content/demo.xml    contenido de ejemplo (WXR) para importar
scripts/build-demo-xml.php  genera demo.xml
```

### Campos por tipo

| Tipo | Campos extra | Layout |
|---|---|---|
| Poema | Del libro (opcional) | Versos con estrofas |
| Traducción | Autor original, título original, idioma, texto original | Dos columnas: original y traducción |
| Libros | Título, autor, año, editorial + portada opcional | Ficha arriba, prosa |
| Cine | Título, dirección, año + portada opcional | Ficha arriba, prosa |
| Nota | ninguno | Prosa |

## Desarrollo

Requiere Node para compilar los estilos. El CSS compilado está versionado, así que el tema funciona sin build.

```
npm install
npm run build      # compila assets/scss → assets/css (style.css y editor.css)
npm run dev        # watch
```

Regenerar el contenido de ejemplo después de editar `scripts/build-demo-xml.php`:

```
php scripts/build-demo-xml.php > demo-content/demo.xml
```

## Instalación

1. Subir la carpeta del tema a `wp-content/themes/juan-felipe-zaldivar` y activarlo.
2. Al activarse, el tema deja los enlaces permanentes en "Nombre de la entrada" y la zona horaria en Buenos Aires si estaban sin configurar.
3. Ajustes → Generales: idioma Español (Argentina).
4. Opcional: en el panel aparece un aviso "¿Querés ver el sitio con contenido de ejemplo?" con un botón que carga `demo-content/demo.xml` (no hace falta ningún plugin). Después se borra desde otro aviso, con otro botón.
5. Apariencia → Opciones del sitio: cargar la URL del newsletter, email de contacto y redes.
6. Apariencia → Menús: crear el menú principal (si no hay ninguno, el tema muestra Inicio, Índice, Eventos y Sobre mí).

## Deploy

`.github/workflows/deploy.yml` sube el tema por FTP en cada push a `master` (y también a mano desde la pestaña Actions). Necesita estos secrets en el repo (Settings → Secrets and variables → Actions):

| Secret | Valor |
|---|---|
| `FTP_HOST` | host FTP del hosting (Hostinger: `ftp.tudominio.com` o la IP que muestra hPanel) |
| `FTP_USER` | usuario FTP |
| `FTP_PASS` | contraseña FTP |
| `FTP_REMOTE_DIR` | carpeta del tema, por ejemplo `/public_html/wp-content/themes/juan-felipe-zaldivar/` |

La carpeta remota se crea sola en el primer deploy. Después de ese primer deploy hay que activar el tema en Apariencia → Temas.
