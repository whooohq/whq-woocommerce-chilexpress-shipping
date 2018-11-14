=== WooCommerce Chilexpress Shipping ===
Contributors: whooo, jhoynerk, tcattd
Tags: woocommerce, shipping, chile, chilexpress
Stable tag: 1.4.9
Requires at least: 4.4
Tested up to: 4.9
Requires PHP: 5.6
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.txt

Añade a Chilexpress como método de envío para WooCommerce.

== Description ==
Añade a Chilexpress como método de envío para WooCommerce.

Utiliza la API de Chilexpress para obtener los costos de envío de forma automática según la región/ciudad/localidad que el usuario seleccione para envío en WooCommerce.

Demo público del plugin disponible en [WPChilexpress](http://wpchilexpress.whooohq.com).

Demostración en video:
[youtube https://www.youtube.com/watch?v=JaLp1wmtKlk]

El soporte al plugin se realiza directamente en [GitHub](https://github.com/whooohq/whq-woocommerce-chilexpress-shipping/).

Un humilde aporte de [Whooo](http://whooohq.com) a la comunidad de WordPress.

== Installation ==
1. Ingresa a tu Administrador (WP-Admin), luego Plugins -> Añadir Nuevo. Busca "WooCommerce Chilexpress Shipping". Presiona "Instalar ahora" y luego Actívalo.
También puedes instalarlo de forma manual: sube el plugin a tu WordPress y actívalo.
2. Luego ve a WooCommerce -> Ajustes -> Envío -> Chilexpress.
3. Configura las opciones. Importante asignar la ciudad de origen (desde donde despacharás los envíos).
Dejar el usuario y password (de la API de Chilexpress) en blanco para utilizar los datos de conexión por defecto (públicos) que Chilexpress provee.
4. Listo.

Ahora tus clientes podrán seleccionar Chilexpress para el envío de sus productos (dentro de Chile) y recibir el cálculo de costo de envío automáticamente.

== Frequently Asked Questions ==
= ¿Puedo ver el plugin funcionando en vivo? =
Claro. Por favor, visita [WPChilexpress](http://wpchilexpress.whooohq.com).

= Extensión SOAP requerida =
La conexión a la API de Chilexpress se realiza a través de SOAP. Por lo tanto, tu servidor debe tener [activada](http://php.net/manual/en/book.soap.php) aquella extensión para poder utilizar este plugin.

= Cálculo de precio de envío =
El cálculo del precio de envío se hace utilizando directamente la API de Chilexpress. Para que la API de Chilexpress entregue los valores correctos, los productos en tu tienda deben tener medidas asignadas (alto, ancho, largo, en cm.) y peso (kg.). De lo contrario, la API de Chilexpress no podrá calcular el precio de envío correctamente.

En el Checkout, el plugin necesita que primero se completen los campos de Región y Ciudad/Localidad para que Chilexpress pueda calcular el precio del envío.

Por otra parte, WooCommerce requiere que uno complete datos obligatorios (como nombre, apellido, fono, email, etc) dentro del Checkout, para recién delegar y realizar el cálculo del envío (independiente del plugin que usen para envíos). Por favor, prueba rellenando primero todos los campos dentro del Checkout, antes de reportar un bug.

= ¿Chilexpress caído? (Chilexpress no disponible) =
Primero que todo, para que un método de envío (en WC) pueda calcular el costo, WC necesita que se llenen los campos obligatorios del formulario del Checkout. Esto es: nombre, apellido, país, dirección, teléfono y correo electrónico, junto a los campos de región y localidad/ciudad (que son poblados automáticamente con los valores que Chilexpress requiere).
Si no rellenan esos datos primero, el checkout seguirá mostrando "no disponible" al lado de Chilexpress.
Una vez rellenados los campos obligatorios y seleccionada la región y localidad/ciudad, Chilexpress debería mostrar el costo del envío en el Checkout.
¿Revisaste si eso es lo que te ocurre?. Si es así, eso es normal.

Habiendo dicho eso, si tu problema persiste, pueden existir un sin fin de otros inconvenientes.

No estamos afiliados ni formamos parte de Chilexpress, por lo que no podemos garantizar el funcionamiento de este plugin al 100%. Si la API de Chilexpress o sus servicios caen (puede ocurrir), no hay nada que podamos hacer al respecto. Si el plugin detecta de la API de Chilexpress no responde, el plugin deja WooCommerce "tal como venía" (ingreso manual de Región y Ciudad), y no permite la selección de Chilexpress como método de envío. No hay nada más que podamos hacer al respecto.

Por otro lado, si vuestro servidor no puede realizar la conexión con los servidores de Chilexpress, el mismo error (Chilexpress no disponible) aparecerá en pantalla durante el checkout, y no podrás utilizar el plugin. Ante eso (problemas de conectividad del servidor), no podemos hacer nada tampoco. Es vuestra responsabilidad debugear aquel punto.

Para más detalle, ver [issue #27](https://github.com/whooohq/whq-woocommerce-chilexpress-shipping/issues/27#issuecomment-321929605).

= ¿Problemas generales, visuales (glitches) y/o funcionales en el carro o finalizar compra (checkout)?
Si vuestro theme presenta glitches visuales, como repetir los campos de región, localidad/ciudad, no muestra el select con las ciudades y localidades que vienen desde la API de Chilexpress, o presenta problemas funcionales que no permiten el paso del carro al checkout, o del checkout al pago, es vuestro deber descartar primero que el problema no sea probocado por una incompatibilidad con otro plugin instalado en WordPress o el theme que se está utilizando en tu instalación de WordPress.

Esto, porque cualquier plugin o theme que altere las funcionalidades por defecto de WooCommerce, también puede quebrar lo que nosotros (así como otros plugins) necesitamos para poder funcionar correctamente.

Para esto, debes:

* Actualizar WordPress, WooCommerce, y nuestro plugin de Chilexpress.

* Desactivar todo plugin en WordPress, a excepción de WooCommerce y nuestro plugin de Chilexpress.

* Instalar el theme recomendado por WooCommerce, [Storefront](https://woocommerce.com/storefront/) (gratuito), y activarlo. Si no puedes activar el theme por ser un sitio en producción, debes instalar el plugin [WP Theme Test](https://wordpress.org/plugins/wp-theme-test/), y con el activar temporalmente el theme para tu cuenta como administrador del sitio.

* Recién ahora debes probar y ver si puedes reproducir el problema que tenías anteriormente.

Si luego de todos esos pasos, el inconveniente aún continúa, por favor repórtalo en un [nuevo issue en GitHub](https://github.com/whooohq/whq-woocommerce-chilexpress-shipping/issues).

Por el contrario, si luego de todos esos pasos el inconveniente ya no se presenta, es vuestro menester encontrar y resolver cualquier incompatibilidad que exista con otro plugin y/o el theme que estás utilizando. No podemos ayudarte en eso, lamentablemente.

Se agradece enormemente si, una vez que encuentres el conflicto que resuelve tu inconveniente, lo compartes con nosotros en un [nuevo issue en GitHub](https://github.com/whooohq/whq-woocommerce-chilexpress-shipping/issues). Así estarás ayudando a muchos otros que podrían encontrarse con tu problema a futuro.

= ¿Errores? ¿Sugerencias? ¿Soporte? =
Reportar errores y enviar sugerencias directamente en [GitHub](https://github.com/whooohq/whq-woocommerce-chilexpress-shipping/issues), por favor.
Todo el soporte del plugin se realiza directo en GitHub. No en los foros de WordPress. No por email. Por favor. Agradecemos la comprensión.

Ayuda y aportes (vía Pull Requests, aceptando la [guía de contribución](https://github.com/whooohq/whq-woocommerce-chilexpress-shipping/blob/master/CONTRIBUTING.md)) son bienvenidos.

¡Gracias!

== Screenshots ==
1. Cálculo precio de envío en Carro de Compras.
2. Cálculo precio de envío en Finalizar Compra.
3. Configuración del plugin.

== Changelog ==
= 1.4.10 =
* Bugfix: arreglo de URLs API Chilexpress QA. Gracias a @PatrickCaneloDigital.
* Mejora: posibilidad de seleccionar ambiente de uso de la API de Chilexpress. Ver [issue #114](https://github.com/whooohq/whq-woocommerce-chilexpress-shipping/issues/114). Agradecimientos por el aviso a @AndresReyesTech
* Mejora: compatibilidad con WordPress Network. Ver [issue #71](https://github.com/whooohq/whq-woocommerce-chilexpress-shipping/issues/71). Gracias a @odayan por el aviso.

= 1.4.7 =
* Mejora: arreglo de compatibilidad con plugin [WooCommerce Chilean Peso](https://wordpress.org/plugins/woocommerce-chilean-peso-currency/), plugin que permite el uso de PayPal en Chile.
* Mejora: aviso de incompatibilidad ahora solo es mostrado en página de Plugins y configuración del Plugin. Ver [issue #103](https://github.com/whooohq/whq-woocommerce-chilexpress-shipping/issues/103).

= 1.4.5 =
* Mejora: compatibilidad con [plugin comercial](https://codecanyon.net/item/woocommerce-shipping-calculator-on-product-page/11496815) para mostrar calculadora de precios en página de producto. Opción recomendada para Shipping Method Input: Display All Shipping With Price (No compatible con opción "Radio"). Advertencia: no hay soporte, por nuestra parte, para el plugin de terceros.
* Mejora: filtros whq_wcchp_shipments_types y whq_wcchp_tarification_rates para cambiar nombres de tipos de envíos. Ver [issue #61](https://github.com/whooohq/whq-woocommerce-chilexpress-shipping/issues/61).

= 1.4.4 =
* Bugfix: resuelve la imposibilidad de pagar por un pedido pendiente desde la lista de pedidos. Ver [issue #104](https://github.com/whooohq/whq-woocommerce-chilexpress-shipping/issues/104). Muchas gracias @truchosky por el aviso ;)
* Mejora: posibilidad de sobre-escribir el tipo algoritmo utilizado para el cálculo del paquete. Agradecimientos a @PatrickCaneloDigital por el trabajo aquí.
* Mejora: posibilidad de sobre-escribir el origen de los paquetes. Ver [issue #97](https://github.com/whooohq/whq-woocommerce-chilexpress-shipping/issues/97).

= 1.3.14 =
* Bugfix: soluciona (otra vez) el problema de selección de tipos de envío soportados en la configuración del plugin. Ver [issue #100](https://github.com/whooohq/whq-woocommerce-chilexpress-shipping/issues/100). Muchas gracias a @PatrickCaneloDigital por reportar el error.

= 1.3.13 =
* Bugfix: soluciona el problema de selección de tipos de envío soportados en la configuración del plugin. Ver [issue #86](https://github.com/whooohq/whq-woocommerce-chilexpress-shipping/issues/86). Muchas gracias a @juanchomix por reportar el error.

= 1.3.12 =
* Bugfix: previene compras con Chilexpress con costo de envío cero, cuando API de Chilexpress no responde correctamente. Ver [issue #81](https://github.com/whooohq/whq-woocommerce-chilexpress-shipping/issues/81). Gracias @PatrickCaneloDigital por reportar el error!

= 1.3.10 =
* Doble checkeo de versión de PHP antes de función conflictiva.

= 1.3.9 =
* Demo público del plugin disponible en [WPChilexpress @ WhoooHQ](http://wpchilexpress.whooohq.com).
* Aclaración en el FAQ (Chilexpress caído, no disponible).

= 1.3.6 =
* Bugfix: corregido error en nuevo método de cálculo de paquetes introducido en v1.3.3. Gracias @PatrickCaneloDigital nuevamente.
* Fixed WooCommerce new version check header tags ([ref](https://woocommerce.wordpress.com/2017/08/28/new-version-check-in-woocommerce-3-2/)).

= 1.3.3 =
* Nuevo método de cálculo de paquetes disponible. Util para casos especiales (productos grandes). [Issue #44](https://github.com/whooohq/whq-woocommerce-chilexpress-shipping/issues/44). ¡Muchas gracias @PatrickCaneloDigital!
* En pausa soporte para Zonas de Envío. Bugs pendientes (configuración desde instancia específica v/s global).

= 1.3.1 =
* Nuevo: Implementada opción para utilizar Chilexpress dentro de las Zonas de Envío de WooCommerce [issue #39](https://github.com/whooohq/whq-woocommerce-chilexpress-shipping/issues/39).
* Nuevo: Agregadas Regiones de Chile para ser utilizadas cuando el plugin es utilizado en las Zonas de Envío.

= 1.2.6 =
* Igualada detección de PHP a algoritmo usado por WooCommerce. A ver si [#42](https://github.com/whooohq/whq-woocommerce-chilexpress-shipping/issues/42) no continua ocurriendo.
* Actualizado el FAQ. Referencia directa a [#27](https://github.com/whooohq/whq-woocommerce-chilexpress-shipping/issues/27#issuecomment-321929605) para futuros issues duplicados.
* SE BUSCAN opiniones en [issue #39](https://github.com/whooohq/whq-woocommerce-chilexpress-shipping/issues/39) respecto a un cambio sugerido en el plugin (Zonas de Envío). Desde ya, gracias por vuestra participación.

= 1.2.5 =
* Bugfix: arreglo de cierta condicion que permitía terminar una compra cuando Chilexpress está sin servicio.

= 1.2.4 =
* Bugfix: arreglo de auto-completado en los navegadores ([#34](https://github.com/whooohq/whq-woocommerce-chilexpress-shipping/issues/34)). Gracias @llermaly por el reporte.

= 1.2.3 =
* Mejoras en el carro de compras y el checkout ([PR#28](https://github.com/whooohq/whq-woocommerce-chilexpress-shipping/pull/28)). ¡Gracias @albetix!
* Limpiadas rutinas javascript del plugin. Agregado debug para desarrollo.
* Implementados headers para aviso de versión mínima de PHP oficial de WP ([ref.](https://make.wordpress.org/plugins/2017/08/29/minimum-php-version-requirement/)) y de WooCommerce ([ref.](https://woocommerce.wordpress.com/2017/08/28/new-version-check-in-woocommerce-3-2/)).
* Actualizada lista de plugins incompatibles ([#18](https://github.com/whooohq/whq-woocommerce-chilexpress-shipping/issues/18))

= 1.2.0 =
* Mejora: ahora puedes seleccionar los típos de envíos disponibles (Ultra Rápido, Overnight, Día hábil siguiente, Día hábil subsiguiente, Tercer día) que deseas soportar en tu tienda, configurables desde las opciones del método de envío ([#26](https://github.com/whooohq/whq-woocommerce-chilexpress-shipping/issues/26))

= 1.1.20 =
* Agregado nuevo plugin en lista de incompatibilidad ([#25](https://github.com/whooohq/whq-woocommerce-chilexpress-shipping/issues/25))
* Mejora: utilizados placeholders de select2 (¡gracias @albetix por la idea!)

= 1.1.17 =
* Fix: https://github.com/whooohq/whq-woocommerce-chilexpress-shipping/issues/22
* Mejora: aumentado el tiempo de caché de las localidades a una semana mínimo, para evitar saturar la API de Chilexpress.
* Actualizada lista (hard-coded) de ciudades y localidades tanto como origen de envío y como para entregas.

= 1.1.13 =
* Mejora: https://github.com/whooohq/whq-woocommerce-chilexpress-shipping/issues/22
* Mejora: https://github.com/whooohq/whq-woocommerce-chilexpress-shipping/pull/23
* Mejorada limpieza de remanentes del plugin en caso de desinstalación.

= 1.1.9 =
* Mejora: calculadora recuerda valores registrados si eres cliente de la tienda (Gracias @albetix).
* Mejora: calculadora conserva valores de región y ciudad (Gracias @albetix).
* Mejora: calculadora pasa valores de región y ciudad al checkout (Gracias @albetix).
* Mejora: checkout pasa valores completos de región y ciudad a la órden de compra, y no los códigos de Chilexpress (Gracias @albetix).
* Fix: checkout ahora calcula independiente de dirección de facturación y/o envío  (Gracias @albetix).
* Fix: mejorada la detección de caida de API Chilexpress.

= 1.1.8 =
* Mejora: https://github.com/whooohq/whq-woocommerce-chilexpress-shipping/issues/15 ¡Gracias @llermaly y @albetix!

= 1.1.6 =
* Mejora: https://github.com/whooohq/whq-woocommerce-chilexpress-shipping/issues/18

= 1.1.5 =
* Fix: https://github.com/whooohq/whq-woocommerce-chilexpress-shipping/issues/17#issuecomment-316171410
* Mejora: caché de ubicaciones mínimo de un día.

= 1.1.3 =
* Fix: https://github.com/whooohq/whq-woocommerce-chilexpress-shipping/issues/14
* Fix: https://github.com/whooohq/whq-woocommerce-chilexpress-shipping/issues/13
* Fix: https://github.com/whooohq/whq-woocommerce-chilexpress-shipping/issues/11#issuecomment-315484722

= 1.1.0 =
* Mejora: implementado el cálculo de precios en la calculadora rápida del carro de compras. Ver: https://www.youtube.com/watch?v=JaLp1wmtKlk
* Mejora: caché de Regiones y Ciudades configurable. Ver opciones del plugin. Default 24 horas.
* Fix: https://github.com/whooohq/whq-woocommerce-chilexpress-shipping/issues/11

= 1.0.9 =
* Fix: https://github.com/whooohq/whq-woocommerce-chilexpress-shipping/issues/10
* Fix: https://github.com/whooohq/whq-woocommerce-chilexpress-shipping/issues/10#issuecomment-314546595

= 1.0.7 =
* Fix: https://github.com/whooohq/whq-woocommerce-chilexpress-shipping/issues/9

= 1.0.6 =
* Fix: https://github.com/whooohq/whq-woocommerce-chilexpress-shipping/issues/6
* Fix: https://github.com/whooohq/whq-woocommerce-chilexpress-shipping/issues/8

= 1.0.5 =
* Fix: https://github.com/whooohq/whq-woocommerce-chilexpress-shipping/issues/4

= 1.0.4 =
* Fix: Transient cache id
* Fix: Cache busting

= 1.0.2 =
* Implementado Select2 para los campos de región y ciudad/localidad.
* Otras mejoras y bugfixes.

= 1.0.1 =
* Primera versión pública.

== Upgrade Notice ==
Activar y configurar.
