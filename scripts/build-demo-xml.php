<?php
/**
 * Genera demo-content/demo.xml (formato WXR 1.2) con contenido de ejemplo.
 * Uso: php scripts/build-demo-xml.php > demo-content/demo.xml
 *
 * Todas las entradas llevan la meta _jfz_demo = 1 para poder borrarlas de una vez desde el admin.
 * Las páginas "Índice" y "Sobre mí" NO son demo: quedan para que el autor las edite.
 */

$site   = 'https://ejemplo.invalid';
$author = 'jfz';
$id     = 1000;

/** Párrafos separados por línea en blanco; dentro de un párrafo, cada línea es un verso (Shift+Enter). */
function verso_blocks( $text ) {
	$out = '';
	foreach ( preg_split( "/\n\s*\n/", trim( $text ) ) as $stanza ) {
		$lines = array_map( 'trim', explode( "\n", trim( $stanza ) ) );
		$out  .= "<!-- wp:paragraph -->\n<p>" . implode( "<br>", array_map( 'esc', $lines ) ) . "</p>\n<!-- /wp:paragraph -->\n\n";
	}
	return trim( $out );
}

/** Prosa: cada bloque separado por línea en blanco es un párrafo. Un bloque que empieza con "## " es un h2. */
function prosa_blocks( $text ) {
	$out = '';
	foreach ( preg_split( "/\n\s*\n/", trim( $text ) ) as $p ) {
		$p = trim( $p );
		if ( str_starts_with( $p, '## ' ) ) {
			$out .= "<!-- wp:heading -->\n<h2 class=\"wp-block-heading\">" . esc( substr( $p, 3 ) ) . "</h2>\n<!-- /wp:heading -->\n\n";
		} else {
			$out .= "<!-- wp:paragraph -->\n<p>" . inline( $p ) . "</p>\n<!-- /wp:paragraph -->\n\n";
		}
	}
	return trim( $out );
}

function esc( $s ) {
	return htmlspecialchars( $s, ENT_NOQUOTES, 'UTF-8' );
}
/** Permite *cursiva* en la prosa. */
function inline( $s ) {
	return preg_replace( '/\*(.+?)\*/', '<em>$1</em>', esc( $s ) );
}
function cdata( $s ) {
	return '<![CDATA[' . str_replace( ']]>', ']]]]><![CDATA[>', $s ) . ']]>';
}
function slugify( $s ) {
	$s = iconv( 'UTF-8', 'ASCII//TRANSLIT', $s );
	$s = strtolower( preg_replace( '/[^A-Za-z0-9]+/', '-', $s ) );
	return trim( $s, '-' );
}

$items = array();

function item( $args ) {
	global $items, $id, $site, $author;
	$id++;
	$defaults = array(
		'type'    => 'post',
		'tipo'    => '',
		'tags'    => array( 'demo' ),
		'meta'    => array(),
		'excerpt' => '',
		'demo'    => true,
		'date'    => '2026-09-01 10:00:00',
		'slug'    => '',
		'status'  => 'publish',
		'template' => '',
	);
	$a = array_merge( $defaults, $args );
	$a['id']   = $id;
	$a['slug'] = $a['slug'] ?: slugify( $a['title'] );
	if ( $a['demo'] ) {
		$a['meta']['_jfz_demo'] = '1';
	}
	if ( $a['template'] ) {
		$a['meta']['_wp_page_template'] = $a['template'];
	}
	$items[] = $a;
}

/* ---------------------------------------------------------------- Poemas */

item( array(
	'title' => 'Inventario de la casa vacía',
	'tipo'  => 'poema',
	'date'  => '2026-09-14 09:30:00',
	'meta'  => array( '_jfz_libro' => 'Inventario' ),
	'content' => verso_blocks( "
Una silla que nadie corre.
Un vaso con la marca de otro vaso.
La luz que entra igual
aunque no haya quien la mire.

Cuento lo que queda
como se cuentan las monedas
al final de un viaje:
no por lo que valen,
por saber que estuvieron.

Afuera un perro ladra
a la misma nada de siempre.
Adentro yo hago la lista
y la lista me hace a mí.
" ),
) );

item( array(
	'title' => 'Lo que el río no dijo',
	'tipo'  => 'poema',
	'date'  => '2026-08-30 18:00:00',
	'content' => verso_blocks( "
El río no dijo nada
y sin embargo lo anoté.

Hay silencios que son agua:
pasan, se llevan la orilla,
dejan una forma nueva
donde antes había un nombre.

Volví al día siguiente
con la libreta abierta.
El río seguía sin decir.
Yo seguí escuchando.
" ),
) );

item( array(
	'title' => 'Domingo',
	'tipo'  => 'poema',
	'date'  => '2026-08-09 11:00:00',
	'content' => verso_blocks( "
La ciudad se queda quieta
como un animal que duerme al sol.

Alguien lava un auto,
alguien discute por teléfono,
alguien no hace nada
y ese alguien soy yo.

El domingo es un cuarto
sin puertas ni ventanas
y sin embargo entra el viento.
" ),
) );

item( array(
	'title' => 'Oficio',
	'tipo'  => 'poema',
	'date'  => '2026-06-21 08:00:00',
	'meta'  => array( '_jfz_libro' => 'Inventario' ),
	'content' => verso_blocks( "
Escribir es poner una piedra
sobre otra piedra
y esperar que no se caigan.

A veces se caen.
Entonces uno mira las piedras
en el suelo, desordenadas,
y descubre que también así
dicen algo.
" ),
) );

item( array(
	'title' => 'Mapa de una ciudad que no existe',
	'tipo'  => 'poema',
	'date'  => '2026-04-03 20:15:00',
	'content' => verso_blocks( "
Acá estaba la casa de mi abuela,
acá el almacén que cerró,
acá la esquina donde alguien
me dijo una cosa que no olvido.

El mapa tiene calles
que solo yo puedo caminar.
Si lo doblo, la ciudad se dobla.
Si lo pierdo, me pierdo yo.
" ),
) );

/* ---------------------------------------------------------- Traducciones */

item( array(
	'title' => 'No soy Nadie. ¿Vos quién sos?',
	'tipo'  => 'traduccion',
	'date'  => '2026-09-06 10:00:00',
	'meta'  => array(
		'_jfz_autor_original'  => 'Emily Dickinson',
		'_jfz_titulo_original' => "I'm Nobody! Who are you?",
		'_jfz_idioma'          => 'inglés',
		'_jfz_texto_original'  => "I'm Nobody! Who are you?\nAre you – Nobody – too?\nThen there's a pair of us!\nDon't tell! they'd advertise – you know!\n\nHow dreary – to be – Somebody!\nHow public – like a Frog –\nTo tell one's name – the livelong June –\nTo an admiring Bog!",
	),
	'content' => verso_blocks( "
No soy Nadie. ¿Vos quién sos?
¿Sos – Nadie – también?
¡Entonces ya somos dos!
No lo digas: nos harían propaganda – ya sabés.

¡Qué triste – ser – Alguien!
Qué público – como una rana –
decir el propio nombre – todo junio –
a un pantano que te admira.
" ),
) );

item( array(
	'title' => 'Autopsicografía',
	'tipo'  => 'traduccion',
	'date'  => '2026-07-18 17:00:00',
	'meta'  => array(
		'_jfz_autor_original'  => 'Fernando Pessoa',
		'_jfz_titulo_original' => 'Autopsicografia',
		'_jfz_idioma'          => 'portugués',
		'_jfz_texto_original'  => "O poeta é um fingidor.\nFinge tão completamente\nQue chega a fingir que é dor\nA dor que deveras sente.\n\nE os que lêem o que escreve,\nNa dor lida sentem bem,\nNão as duas que ele teve,\nMas só a que eles não têm.\n\nE assim nas calhas de roda\nGira, a entreter a razão,\nEsse comboio de corda\nQue se chama coração.",
	),
	'content' => verso_blocks( "
El poeta es un fingidor.
Finge tan completamente
que llega a fingir que es dolor
el dolor que de veras siente.

Y los que leen lo que escribe,
en el dolor leído sienten bien,
no los dos que él tuvo,
sino solo el que ellos no tienen.

Y así, por los rieles de la rueda,
gira, entreteniendo a la razón,
ese trencito de cuerda
que se llama corazón.
" ),
) );

item( array(
	'title' => 'Día de otoño',
	'tipo'  => 'traduccion',
	'date'  => '2026-05-11 09:00:00',
	'meta'  => array(
		'_jfz_autor_original'  => 'Rainer Maria Rilke',
		'_jfz_titulo_original' => 'Herbsttag',
		'_jfz_idioma'          => 'alemán',
		'_jfz_texto_original'  => "Herr: es ist Zeit. Der Sommer war sehr groß.\nLeg deinen Schatten auf die Sonnenuhren,\nund auf den Fluren laß die Winde los.\n\nBefiehl den letzten Früchten voll zu sein;\ngib ihnen noch zwei südlichere Tage,\ndränge sie zur Vollendung hin und jage\ndie letzte Süße in den schweren Wein.\n\nWer jetzt kein Haus hat, baut sich keines mehr.\nWer jetzt allein ist, wird es lange bleiben,\nwird wachen, lesen, lange Briefe schreiben\nund wird in den Alleen hin und her\nunruhig wandern, wenn die Blätter treiben.",
	),
	'content' => verso_blocks( "
Señor: es tiempo. El verano fue muy grande.
Poné tu sombra sobre los relojes de sol
y soltá los vientos en los campos.

Ordená a los últimos frutos que maduren;
dales todavía dos días más del sur,
empujalos a completarse y apurá
la última dulzura en el vino pesado.

Quien ahora no tiene casa, ya no la construye.
Quien ahora está solo, lo estará por mucho tiempo:
va a velar, a leer, a escribir cartas largas,
y va a andar por las alamedas, de acá para allá,
inquieto, cuando se muevan las hojas.
" ),
) );

/* ---------------------------------------------------------------- Libros */

item( array(
	'title' => 'Leer a Rulfo de noche',
	'tipo'  => 'libros',
	'date'  => '2026-09-10 21:00:00',
	'tags'  => array( 'demo', 'narrativa mexicana' ),
	'meta'  => array(
		'_jfz_obra_titulo'    => 'Pedro Páramo',
		'_jfz_obra_autor'     => 'Juan Rulfo',
		'_jfz_obra_anio'      => '1955',
		'_jfz_obra_editorial' => 'Fondo de Cultura Económica',
	),
	'content' => prosa_blocks( "
Hay libros que piden una hora. *Pedro Páramo* pide la noche: no por efecto, sino porque su lógica es la del insomnio, la de las voces que se oyen cuando ya no hay nada que las tape. Volví a leerlo esta semana, de un tirón, y me pasó lo mismo que la primera vez: al terminar no sabía bien cuántos personajes estaban vivos.

Rulfo no explica. Corta. Cada fragmento es una lápida con una frase grabada, y el lector camina entre ellas armando la historia como puede. Esa economía, que en otro sería pobreza, acá es una forma de respeto: confía en que uno escuche.

## Lo que se pierde en la relectura

Lo que se pierde es la sorpresa del mecanismo. Lo que se gana es otra cosa: la posibilidad de detenerse en los murmullos, en las frases que en la primera lectura pasan como paisaje. \"Este pueblo está lleno de ecos.\" Uno vuelve y los ecos son otros.

Si tuviera que decir de qué trata, diría que trata de la imposibilidad de irse. Comala no retiene a nadie por la fuerza; retiene porque afuera no hay nada que no sea también Comala.
" ),
) );

item( array(
	'title' => 'Bartleby, o el arte de no',
	'tipo'  => 'libros',
	'date'  => '2026-07-02 12:00:00',
	'meta'  => array(
		'_jfz_obra_titulo' => 'Bartleby, el escribiente',
		'_jfz_obra_autor'  => 'Herman Melville',
		'_jfz_obra_anio'   => '1853',
	),
	'content' => prosa_blocks( "
\"Preferiría no hacerlo.\" La frase se volvió lema de remera y eso, en cierto modo, es un triunfo del cuento: sobrevivió a su propia explicación. Pero cada vez que lo releo me interesa menos Bartleby y más el abogado que narra, ese hombre razonable que no sabe qué hacer con alguien que simplemente no colabora.

Melville escribe una comedia de oficina que se va convirtiendo en otra cosa sin que uno note el momento del cambio. Es un relato sobre la caridad y sus límites, sobre lo que hacemos con los que no encajan en ninguna de nuestras categorías de ayuda.

Hay una lectura política, hay una lectura teológica, hay una lectura sobre la escritura misma (un copista que deja de copiar). Todas funcionan y ninguna cierra. Eso es lo que lo mantiene vivo.
" ),
) );

item( array(
	'title' => 'Los cuadernos de Malte: la ciudad como herida',
	'tipo'  => 'libros',
	'date'  => '2026-04-19 10:30:00',
	'meta'  => array(
		'_jfz_obra_titulo' => 'Los cuadernos de Malte Laurids Brigge',
		'_jfz_obra_autor'  => 'Rainer Maria Rilke',
		'_jfz_obra_anio'   => '1910',
	),
	'content' => prosa_blocks( "
Rilke llegó a París y la ciudad lo enfermó. De esa enfermedad salió este libro, que no es novela ni diario ni ensayo, o es las tres cosas turnándose. Malte camina, mira, recuerda su infancia en Dinamarca, y todo lo que ve parece estar a punto de romperse.

\"Así que aquí vienen las personas a vivir; yo más bien pensaría que aquí se muere.\" Es la primera frase y ya está todo: la mirada que convierte cada esquina en un síntoma.

Lo leí mientras traducía *Día de otoño* y me sirvió para entender algo del tono: en Rilke la desolación no es un grito, es una atención excesiva. Se mira demasiado y por eso duele.
" ),
) );

/* ------------------------------------------------------------------ Cine */

item( array(
	'title' => 'La zona y el deseo',
	'tipo'  => 'cine',
	'date'  => '2026-08-22 23:00:00',
	'meta'  => array(
		'_jfz_obra_titulo'   => 'Stalker',
		'_jfz_obra_director' => 'Andréi Tarkovski',
		'_jfz_obra_anio'     => '1979',
	),
	'content' => prosa_blocks( "
Tres hombres caminan hacia un cuarto donde, dicen, se cumplen los deseos. Tardan dos horas y media en llegar y cuando llegan no entran. Contada así, *Stalker* parece una broma. Vista, es una de las experiencias más físicas que conozco en el cine: uno sale con el cuerpo cansado, como si hubiera caminado con ellos.

Tarkovski filma el tiempo. No la acción, no la trama: el tiempo pasando sobre las cosas, el agua que gotea, la hierba que se mueve. La Zona no es un lugar peligroso porque haya trampas; es peligrosa porque obliga a estar presente.

Me interesa sobre todo el personaje del Escritor, que va a la Zona buscando inspiración y encuentra que su cinismo no le sirve de nada ahí. Hay una lección para cualquiera que escriba, aunque prefiero no formularla.
" ),
) );

item( array(
	'title' => 'El calor que no se va',
	'tipo'  => 'cine',
	'date'  => '2026-05-30 19:00:00',
	'tags'  => array( 'demo', 'cine argentino' ),
	'meta'  => array(
		'_jfz_obra_titulo'   => 'La ciénaga',
		'_jfz_obra_director' => 'Lucrecia Martel',
		'_jfz_obra_anio'     => '2001',
	),
	'content' => prosa_blocks( "
Una pileta con agua podrida, una familia que se arrastra por una casa en Salta, un accidente que nadie termina de atender. *La ciénaga* es una película sin argumento en el sentido tradicional y, sin embargo, uno sabe en cada momento exactamente qué está pasando: algo se pudre y nadie hace nada.

Martel trabaja con el sonido como pocos. Los truenos que no traen lluvia, los vasos que se rompen fuera de cuadro, los gritos de los chicos que se mezclan con la televisión. El malestar entra por el oído antes que por los ojos.

La vi por primera vez a los veinte años y me pareció lenta. La volví a ver ahora y me pareció exacta. No cambió la película.
" ),
) );

/* ------------------------------------------------------------------ Nota */

item( array(
	'title' => 'Sobre traducir, o por qué elegí mal la palabra',
	'tipo'  => 'nota',
	'date'  => '2026-06-05 15:00:00',
	'content' => prosa_blocks( "
Cuando traduje el poema de Dickinson dudé mucho en el primer verso. \"Who are you?\" puede ser \"¿quién eres?\" o \"¿quién sos?\", y esa decisión mínima define todo lo demás: el tono, la distancia, el país desde el que se habla.

Elegí el voseo porque es la lengua en la que pienso, y porque Dickinson escribía con una intimidad que en mi oído suena a vos y no a tú. Pero sé que es una elección, no una verdad. Cada traducción es una versión posible, y esta página va a tener, con el tiempo, varias versiones del mismo poema. No me parece un problema. Me parece la única manera honesta de hacerlo.

Esta nota es también una explicación de método: cuando publico una traducción, dejo el original al lado. No para que se compare como en un examen, sino para que el lector tenga las dos músicas.
" ),
) );

/* --------------------------------------------------------------- Eventos */

item( array(
	'type'  => 'evento',
	'title' => 'Lectura: poemas nuevos y traducciones',
	'date'  => '2026-09-12 12:00:00',
	'tags'  => array(),
	'excerpt' => 'Lectura de textos inéditos y de las traducciones de Dickinson y Rilke, con conversación al final.',
	'meta'  => array(
		'_jfz_evento_fecha'      => '2026-10-24',
		'_jfz_evento_hora'       => '19:30',
		'_jfz_evento_lugar'      => 'Café Literario del Bajo',
		'_jfz_evento_ciudad'     => 'Buenos Aires',
		'_jfz_evento_link'       => 'https://ejemplo.invalid/reservas',
		'_jfz_evento_link_texto' => 'Reservar lugar',
	),
	'content' => prosa_blocks( "
Voy a leer poemas de *Inventario* y algunos textos nuevos que todavía no publiqué acá. Después, un rato de traducciones: Dickinson, Pessoa y Rilke, con los originales a mano para quien quiera seguirlos.

La entrada es libre pero el lugar es chico, así que conviene reservar.
" ),
) );

item( array(
	'type'  => 'evento',
	'title' => 'Presentación de Inventario',
	'date'  => '2026-07-20 12:00:00',
	'tags'  => array(),
	'excerpt' => 'Presentación del libro con lectura y conversación.',
	'meta'  => array(
		'_jfz_evento_fecha'  => '2026-08-12',
		'_jfz_evento_hora'   => '19:00',
		'_jfz_evento_lugar'  => 'Librería El Ateneo del Barrio',
		'_jfz_evento_ciudad' => 'Buenos Aires',
	),
	'content' => prosa_blocks( "
Presentación de *Inventario*, mi primer libro de poemas. Leí algunos textos y conversamos sobre el proceso de escritura. Gracias a todos los que vinieron.
" ),
) );

/* --------------------------------------------------------------- Páginas */

item( array(
	'type'     => 'page',
	'title'    => 'Índice',
	'slug'     => 'indice',
	'demo'     => false,
	'tags'     => array(),
	'date'     => '2026-03-01 10:00:00',
	'template' => 'page-templates/indice.php',
	'content'  => prosa_blocks( "
Todos los textos publicados en este sitio, agrupados por tipo. Los más recientes van primero.
" ),
) );

item( array(
	'type'    => 'page',
	'title'   => 'Sobre mí',
	'slug'    => 'sobre-mi',
	'demo'    => false,
	'tags'    => array(),
	'date'    => '2026-03-01 10:00:00',
	'content' => prosa_blocks( "
[Texto de ejemplo. Editá esta página desde el admin: Páginas → Sobre mí.]

Soy Juan Felipe Zaldívar. Escribo poemas, traduzco poemas de otros y anoto lo que leo y lo que veo. Este sitio es el lugar donde junto todo eso, sin más orden que el de las fechas.

## Libros

*Inventario* (poemas). [Completar con editorial y año.]

## Contacto

Se puede escribir a la dirección que figura al pie de la página, o seguir las novedades por correo suscribiéndose más abajo.
" ),
) );

/* ------------------------------------------------------------- Salida XML */

$tipos = array(
	'poema'      => 'Poema',
	'traduccion' => 'Traducción',
	'libros'     => 'Libros',
	'cine'       => 'Cine',
	'nota'       => 'Nota',
);
$tags = array();
foreach ( $items as $it ) {
	foreach ( $it['tags'] as $t ) {
		$tags[ slugify( $t ) ] = $t;
	}
}

echo '<?xml version="1.0" encoding="UTF-8" ?>' . "\n";
echo '<!-- Contenido de ejemplo del tema Juan Felipe Zaldívar. Importar desde Herramientas → Importar → WordPress. -->' . "\n";
echo '<rss version="2.0" xmlns:excerpt="http://wordpress.org/export/1.2/excerpt/" xmlns:content="http://purl.org/rss/1.0/modules/content/" xmlns:wfw="http://wellformedweb.org/CommentAPI/" xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:wp="http://wordpress.org/export/1.2/">' . "\n";
echo "<channel>\n";
echo "\t<title>Juan Felipe Zaldívar</title>\n\t<link>$site</link>\n\t<description>Contenido de ejemplo</description>\n\t<pubDate>" . date( 'r' ) . "</pubDate>\n\t<language>es-AR</language>\n";
echo "\t<wp:wxr_version>1.2</wp:wxr_version>\n\t<wp:base_site_url>$site</wp:base_site_url>\n\t<wp:base_blog_url>$site</wp:base_blog_url>\n";
echo "\t<wp:author><wp:author_id>1</wp:author_id><wp:author_login>" . cdata( $author ) . "</wp:author_login><wp:author_email>" . cdata( 'jfz@ejemplo.invalid' ) . "</wp:author_email><wp:author_display_name>" . cdata( 'Juan Felipe Zaldívar' ) . "</wp:author_display_name><wp:author_first_name>" . cdata( 'Juan Felipe' ) . "</wp:author_first_name><wp:author_last_name>" . cdata( 'Zaldívar' ) . "</wp:author_last_name></wp:author>\n";

$tid = 1;
foreach ( $tipos as $slug => $name ) {
	$tid++;
	echo "\t<wp:term><wp:term_id>$tid</wp:term_id><wp:term_taxonomy>" . cdata( 'tipo' ) . "</wp:term_taxonomy><wp:term_slug>" . cdata( $slug ) . "</wp:term_slug><wp:term_name>" . cdata( $name ) . "</wp:term_name></wp:term>\n";
}
foreach ( $tags as $slug => $name ) {
	$tid++;
	echo "\t<wp:tag><wp:term_id>$tid</wp:term_id><wp:tag_slug>" . cdata( $slug ) . "</wp:tag_slug><wp:tag_name>" . cdata( $name ) . "</wp:tag_name></wp:tag>\n";
}

foreach ( $items as $it ) {
	$link = 'page' === $it['type'] ? "$site/{$it['slug']}/" : ( 'evento' === $it['type'] ? "$site/evento/{$it['slug']}/" : "$site/{$it['slug']}/" );
	$gmt  = gmdate( 'Y-m-d H:i:s', strtotime( $it['date'] . ' -03:00' ) );
	echo "\t<item>\n";
	echo "\t\t<title>" . cdata( $it['title'] ) . "</title>\n";
	echo "\t\t<link>$link</link>\n";
	echo "\t\t<pubDate>" . date( 'r', strtotime( $it['date'] . ' -03:00' ) ) . "</pubDate>\n";
	echo "\t\t<dc:creator>" . cdata( $author ) . "</dc:creator>\n";
	echo "\t\t<guid isPermaLink=\"false\">$site/?p={$it['id']}</guid>\n";
	echo "\t\t<description></description>\n";
	echo "\t\t<content:encoded>" . cdata( $it['content'] ) . "</content:encoded>\n";
	echo "\t\t<excerpt:encoded>" . cdata( $it['excerpt'] ) . "</excerpt:encoded>\n";
	echo "\t\t<wp:post_id>{$it['id']}</wp:post_id>\n";
	echo "\t\t<wp:post_date>" . cdata( $it['date'] ) . "</wp:post_date>\n";
	echo "\t\t<wp:post_date_gmt>" . cdata( $gmt ) . "</wp:post_date_gmt>\n";
	echo "\t\t<wp:post_modified>" . cdata( $it['date'] ) . "</wp:post_modified>\n";
	echo "\t\t<wp:post_modified_gmt>" . cdata( $gmt ) . "</wp:post_modified_gmt>\n";
	echo "\t\t<wp:comment_status>" . cdata( 'closed' ) . "</wp:comment_status>\n";
	echo "\t\t<wp:ping_status>" . cdata( 'closed' ) . "</wp:ping_status>\n";
	echo "\t\t<wp:post_name>" . cdata( $it['slug'] ) . "</wp:post_name>\n";
	echo "\t\t<wp:status>" . cdata( $it['status'] ) . "</wp:status>\n";
	echo "\t\t<wp:post_parent>0</wp:post_parent>\n\t\t<wp:menu_order>0</wp:menu_order>\n";
	echo "\t\t<wp:post_type>" . cdata( $it['type'] ) . "</wp:post_type>\n";
	echo "\t\t<wp:post_password>" . cdata( '' ) . "</wp:post_password>\n\t\t<wp:is_sticky>0</wp:is_sticky>\n";
	if ( $it['tipo'] ) {
		echo "\t\t<category domain=\"tipo\" nicename=\"{$it['tipo']}\">" . cdata( $tipos[ $it['tipo'] ] ) . "</category>\n";
	}
	foreach ( $it['tags'] as $t ) {
		echo "\t\t<category domain=\"post_tag\" nicename=\"" . slugify( $t ) . "\">" . cdata( $t ) . "</category>\n";
	}
	foreach ( $it['meta'] as $k => $v ) {
		echo "\t\t<wp:postmeta><wp:meta_key>" . cdata( $k ) . "</wp:meta_key><wp:meta_value>" . cdata( $v ) . "</wp:meta_value></wp:postmeta>\n";
	}
	echo "\t</item>\n";
}
echo "</channel>\n</rss>\n";
