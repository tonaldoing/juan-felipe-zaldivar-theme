# Manual del sitio

Guía corta para publicar en el sitio desde el panel de WordPress.

## 1. Publicar un texto

1. **Entradas → Añadir nueva.**
2. Escribí el título y el texto.
3. Abajo de todo hay una barra plegable (dice "Meta Boxes" o "Cajas meta"). Al abrirla, en **Tipo de texto** elegí uno: Poema, Traducción, Libros, Cine o Nota. Es obligatorio y es uno solo.
4. Según el tipo, justo debajo, en **Detalles según el tipo de texto** aparecen los campos extra (ver abajo).
5. **Publicar.**

Etiquetas: son opcionales. Sirven para agrupar por autor traducido, tema, etc. No hace falta usarlas.

Extracto: opcional. Si lo cargás, en la portada se muestra ese texto en vez de las primeras líneas.

### Poemas

- Cada **estrofa** es un párrafo: Enter para empezar una estrofa nueva.
- Cada **verso** dentro de la estrofa se separa con **Shift + Enter** (salto de línea sin cambiar de párrafo).
- También podés usar el bloque **Verso** del editor, que respeta todos los espacios tal cual los escribas.
- Campo opcional: *Del libro*, si el poema pertenece a un libro publicado.

En la portada, un poema muestra sus primeros cuatro versos.

### Traducciones

- El **título** de la entrada es el título en castellano.
- El texto de la entrada es **tu traducción** (mismo formato que los poemas).
- Campos: autor original, título original, idioma original y **texto original**. Si cargás el texto original, la página muestra las dos versiones lado a lado (en el celular, una debajo de la otra). Si lo dejás vacío, se ve solo la traducción.

### Libros y Cine

- El **título** de la entrada es el título de tu nota, no el del libro o la película.
- Campos: título de la obra, autor o dirección, año, editorial (libros). Se muestran en una ficha arriba del texto.
- **Imagen destacada** (panel de la derecha): opcional, para la portada del libro o el afiche. Se muestra en la ficha.

### Notas

Textos varios. Sin campos extra.

## 2. Eventos

1. **Eventos → Agregar evento.**
2. Título, texto de presentación y, en **Datos del evento**: fecha, hora, lugar, ciudad, link (entradas, mapa, más info) y texto del botón.
3. Opcional: un extracto corto, que se muestra en el listado de eventos.

El evento con la fecha más próxima aparece **destacado arriba de la portada**. El día después de la fecha desaparece solo de la portada y pasa a "Eventos pasados" en `/eventos/`. No hay que hacer nada.

## 3. Índice y Sobre mí

- **Índice** (`/indice/`): se arma solo con todos los textos publicados, agrupados por tipo. Podés editar el párrafo de introducción desde Páginas → Índice. No borres la página.
- **Sobre mí** (`/sobre-mi/`): editala desde Páginas → Sobre mí. Viene con un texto de ejemplo para reemplazar. La imagen destacada, si la cargás, aparece arriba del texto.

## 4. Newsletter, contacto y redes

**Apariencia → Opciones del sitio.**

- **Newsletter**: pegá la URL de acción del formulario de tu proveedor (Buttondown, Mailchimp, Substack…) y el nombre del campo de email que usa (Buttondown y Substack: `email`; Mailchimp: `EMAIL`). Si la URL queda vacía, la caja de suscripción no se muestra.
- **Contacto y redes**: email, Instagram y una red más. Aparecen en el pie.
- **Textos del sitio**: la frase del pie y la etiqueta del aviso de evento ("Próximo evento").

## 5. Contenido de ejemplo

Con el sitio vacío, el panel muestra un aviso **"¿Querés ver el sitio con contenido de ejemplo?"** con el botón **Cargar contenido de ejemplo**. Carga textos y eventos de muestra para ver cómo se ve todo. Mientras existan textos de muestra, en el panel aparece otro aviso: **"Hay contenido de ejemplo cargado"** con el botón **Borrar contenido de ejemplo**. Un clic los borra a todos de una vez (no pasan por la papelera). Las páginas Índice y Sobre mí no se borran.

Si preferís borrarlos de a uno, todos tienen la etiqueta `demo`.

## 6. Menú

Si no creás ningún menú, el sitio muestra Inicio, Índice, Eventos y Sobre mí. Para cambiarlo: **Apariencia → Menús**, crear un menú y asignarlo a la ubicación "Menú principal". Hay una segunda ubicación, "Menú del pie", opcional.

## 7. Cosas que el sitio hace solo

- Feed RSS general en `/feed/` y por tipo (por ejemplo `/tipo/poema/feed/`).
- Modo oscuro si el visitante lo tiene configurado en su dispositivo.
- Buscador en el pie de todas las páginas.
- Sin comentarios: no hay nada que moderar.
