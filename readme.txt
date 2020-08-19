=== Chilexpress Shipping for WooCommerce ===
Contributors: whooo, jhoynerk, tcattd
Tags: woocommerce, shipping, chile, chilexpress
Stable tag: 1.4.24
Requires at least: 5.0
Tested up to: 5.5
Requires PHP: 7.2
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
[Ver registro de cambios en GitHub](https://github.com/whooohq/whq-woocommerce-chilexpress-shipping/blob/master/CHANGELOG.md).

== Upgrade Notice ==
Activar y configurar.
