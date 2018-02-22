# WooCommerce Chilexpress Shipping

## Descripción
Añade a Chilexpress como método de envío para WooCommerce.

Utiliza la API de Chilexpress para obtener los costos de envío de forma automática según la región/ciudad/localidad que el usuario seleccione para envío en WooCommerce.

Demo público del plugin disponible en [WPChilexpress](http://wpchilexpress.whooohq.com).

Demostración en video:

[![Demostración](https://img.youtube.com/vi/JaLp1wmtKlk/0.jpg)](https://www.youtube.com/watch?v=JaLp1wmtKlk)

El soporte al plugin se realiza directamente en [GitHub](https://github.com/whooohq/whq-woocommerce-chilexpress-shipping/issues).

Un humilde aporte de [Whooo](http://whooohq.com) a la comunidad de WordPress.


## Instalación
Ver página oficial del plugin en [WordPress.org](https://wordpress.org/plugins/woo-chilexpress-shipping/).


## Preguntas Frecuentes

### ¿Puedo ver el plugin funcionando en vivo?
Claro. Visita [WPChilexpress](http://wpchilexpress.whooohq.com).


### Extensión SOAP requerida.
La conexión a la API de Chilexpress se realiza a través de SOAP. Por lo tanto, tu servidor debe tener [activada](http://php.net/manual/en/book.soap.php) aquella extensión para poder utilizar este plugin.


### Cálculo de precio de envío
El cálculo del precio de envío se hace utilizando directamente la API de Chilexpress. Para que la API de Chilexpress entregue los valores correctos, los productos en tu tienda deben tener medidas asignadas (alto, ancho, largo, en cm.) y peso (kg.). De lo contrario, la API de Chilexpress no podrá calcular el precio de envío correctamente.


### ¿Chilexpress caído? (Chilexpress no disponible)
Primero que todo, para que un método de envío (en WC) pueda calcular el costo, WC necesita que se llenen los campos obligatorios del formulario del Checkout. Esto es: nombre, apellido, país, dirección, teléfono y correo electrónico, junto a los campos de región y localidad/ciudad (que son poblados automáticamente con los valores que Chilexpress requiere).
Si no rellenan esos datos primero, el checkout seguirá mostrando "no disponible" al lado de Chilexpress.
Una vez rellenados los campos obligatorios y seleccionada la región y localidad/ciudad, Chilexpress debería mostrar el costo del envío en el Checkout.
¿Revisaste si eso es lo que te ocurre?. Si es así, eso es normal.

Habiendo dicho eso, si tu problema persiste, pueden existir un sin fin de otros inconvenientes.

No estamos afiliados ni formamos parte de Chilexpress, por lo que no podemos garantizar el funcionamiento de este plugin al 100%. Si la API de Chilexpress o sus servicios caen (puede ocurrir), no hay nada que podamos hacer al respecto. Si el plugin detecta de la API de Chilexpress no responde, el plugin deja WooCommerce "tal como venía" (ingreso manual de Región y Ciudad), y no permite la selección de Chilexpress como método de envío. No hay nada más que podamos hacer al respecto.

Por otro lado, si vuestro servidor no puede realizar la conexión con los servidores de Chilexpress, el mismo error (Chilexpress no disponible) aparecerá en pantalla durante el checkout, y no podrás utilizar el plugin. Ante eso (problemas de conectividad del servidor), no podemos hacer nada tampoco. Es vuestra responsabilidad debugear aquel punto.

Para más detalle, ver [issue #27](https://github.com/whooohq/whq-woocommerce-chilexpress-shipping/issues/27#issuecomment-321929605).


### ¿Errores? ¿Sugerencias? ¿Soporte?
Reportar errores y enviar sugerencias directamente en [GitHub](https://github.com/whooohq/whq-woocommerce-chilexpress-shipping/issues), por favor.

Todo el soporte del plugin se realiza directo en GitHub. No en los foros de WordPress. No por email. Por favor. Agradecemos la comprensión.

Ayuda y aportes (vía Pull Requests, aceptando la [guía de contribución](https://github.com/whooohq/whq-woocommerce-chilexpress-shipping/blob/master/CONTRIBUTING.md)) son bienvenidos.

¡Gracias!

### Licencia
[GPLv2 or later](https://github.com/whooohq/whq-woocommerce-chilexpress-shipping/blob/master/LICENSE)
