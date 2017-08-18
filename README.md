# WooCommerce Chilexpress Shipping

## Descripción
Añade a Chilexpress como método de envío para WooCommerce.

Utiliza la API de Chilexpress para obtener los costos de envío de forma automática según la región/ciudad/localidad que el usuario seleccione para envío en WooCommerce.

Demostración en video:

[![Demostración](https://img.youtube.com/vi/JaLp1wmtKlk/0.jpg)](https://www.youtube.com/watch?v=JaLp1wmtKlk)

El soporte al plugin se realiza directamente en [GitHub](https://github.com/whooohq/whq-woocommerce-chilexpress-shipping/issues).

Un humilde aporte de [Whooo](http://whooohq.com) a la comunidad de WordPress.


## Instalación
Ver página oficial del plugin en [WordPress.org](https://wordpress.org/plugins/woo-chilexpress-shipping/).


## Preguntas Frecuentes

### Extensión SOAP requerida.
La conexión a la API de Chilexpress se realiza a través de SOAP. Por lo tanto, tu servidor debe tener [activada](http://php.net/manual/en/book.soap.php) aquella extensión para poder utilizar este plugin.


### Cálculo de precio de envío
El cálculo del precio de envío se hace utilizando directamente la API de Chilexpress. Para que la API de Chilexpress entregue los valores correctos, los productos en tu tienda deben tener medidas asignadas (alto, ancho, largo, en cm.) y peso (kg.). De lo contrario, la API de Chilexpress no podrá calcular el precio de envío correctamente.


### ¿Chilexpress caido?
No estamos afiliados ni formamos parte de Chilexpress, por lo que no podemos garantizar el funcionamiento de este plugin al 100%. Si la API de Chilexpress o sus servicios caen (puede ocurrir), no hay nada que podamos hacer al respecto. Si el plugin detecta de la API de Chilexpress no responde, el plugin deja WooCommerce "tal como venía" (ingreso manual de Región y Ciudad), y no permite la selección de Chilexpress como método de envío. No hay nada más que podamos hacer al respecto.


### ¿Errores? ¿Sugerencias?
Reportar errores y enviar sugerencias directamente en [GitHub](https://github.com/whooohq/whq-woocommerce-chilexpress-shipping/issues), por favor.

Ayuda y aportes (vía Pull Requests, aceptando la [guía de contribución](https://github.com/whooohq/whq-woocommerce-chilexpress-shipping/blob/master/CONTRIBUTING.md)) son bienvenidos.

¡Gracias!

### Licencia
[GPLv2 or later](https://github.com/whooohq/whq-woocommerce-chilexpress-shipping/blob/master/LICENSE)
