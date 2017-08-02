=== WooCommerce Chilexpress Shipping ===
Contributors: whooo, jhoynerk, tcattd
Tags: woocommerce, shipping, chile, chilexpress
Stable tag: 1.1.18
Requires at least: 4.4
Tested up to: 4.8
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.txt

Añade a Chilexpress como método de envío para WooCommerce.

== Description ==
Añade a Chilexpress como método de envío para WooCommerce.

Utiliza la API de Chilexpress para obtener los costos de envío de forma automática según la región/ciudad/localidad que el usuario seleccione para envío en WooCommerce.

Demostración en video:
[youtube https://www.youtube.com/watch?v=JaLp1wmtKlk]

El soporte al plugin se realiza directamente en [GitHub](https://github.com/whooohq/whq-woocommerce-chilexpress-shipping/).

== Installation ==
1. Ingresa a tu Administrador (WP-Admin), luego Plugins -> Añadir Nuevo. Busca "WooCommerce Chilexpress Shipping". Presiona "Instalar ahora" y luego Actívalo.
También puedes instalarlo de forma manual: sube el plugin a tu WordPress y actívalo.
2. Luego ve a WooCommerce -> Ajustes -> Envío -> Chilexpress.
3. Configura las opciones. Importante asignar la ciudad de origen (desde donde despacharás los envíos).
Dejar el usuario y password (de la API de Chilexpress) en blanco para utilizar los datos de conexión por defecto (públicos) que Chilexpress provee.
4. Listo.

Ahora tus clientes podrán seleccionar Chilexpress para el envío de sus productos (dentro de Chile) y recibir el cálculo de costo de envío automáticamente.

== Frequently Asked Questions ==
= Extensión SOAP requerida =
La conexión a la API de Chilexpress se realiza a través de SOAP. Por lo tanto, tu servidor debe tener [activada](http://php.net/manual/en/book.soap.php) aquella extensión para poder utilizar este plugin.

= Cálculo de precio de envío =
El cálculo del precio de envío se hace utilizando directamente la API de Chilexpress. Para que la API de Chilexpress entregue los valores correctos, los productos en tu tienda deben tener medidas asignadas (alto, ancho, largo, en cm.) y peso (kg.). De lo contrario, la API de Chilexpress no podrá calcular el precio de envío correctamente.

= ¿Chilexpress caido? =
No estamos afiliados ni formamos parte de Chilexpress, por lo que no podemos garantizar el funcionamiento de este plugin al 100%. Si la API de Chilexpress o sus servicios caen (puede ocurrir), no hay nada que podamos hacer al respecto. Si el plugin detecta de la API de Chilexpress no responde, el plugin deja WooCommerce "tal como venía" (ingreso manual de Región y Ciudad), y no permite la selección de Chilexpress como método de envío. No hay nada más que podamos hacer al respecto.

= ¿Errores? ¿Sugerencias? =
Reportar errores y enviar sugerencias directamente en [GitHub](https://github.com/whooohq/whq-woocommerce-chilexpress-shipping/issues), por favor.

Ayuda y aportes (pull requests) son bienvenidos.

¡Gracias!

== Screenshots ==
1. Cálculo precio de envío en Carro de Compras.
2. Cálculo precio de envío en Finalizar Compra.
3. Configuración del plugin.

== Changelog ==
= 1.1.18 =
* Mejora: https://github.com/whooohq/whq-woocommerce-chilexpress-shipping/issues/25

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
