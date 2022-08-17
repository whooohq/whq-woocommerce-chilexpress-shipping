# Changelog

### 1.6.0
* Fin al soporte del plugin. [Leer más](https://github.com/whooohq/whq-woocommerce-chilexpress-shipping/discussions/248).

### 1.5.2
* Experimental: posiblidad de seleccionar y usar la nueva API REST de Chilexpress. Se agradece enormemente el trabajo de @PatrickCaneloDigital implementando esta característica. Ver [issue #234](https://github.com/whooohq/whq-woocommerce-chilexpress-shipping/issues/234).
* Corregidos PHP Warnings recurrentes. Gracias @linkini por el avios. Ver [issue #240](https://github.com/whooohq/whq-woocommerce-chilexpress-shipping/issues/240).

### 1.4.46
* Corregido problema de inicialización del plugin para instalaciones nuevas de WooCommerce, por cambio en API de WooCommerce desde la versión 6.0 en adelante. Gracias @NVMGFC por el aviso. Ver [issue #236](https://github.com/whooohq/whq-woocommerce-chilexpress-shipping/issues/236).

### 1.4.45
* Corregido problema por cambios en la API SOAP de Chilexpress. No hay forma de adelantarse a estos cambios, considerando que somos una solución no oficial. Lo siento, de verdad. Muchas gracias a la comunidad por el aviso (ver notas de versión 1.4.43), y la paciencia de todos ustedes.
* Agregada opción para cambiar automáticamente el orden de los campos de Región y Ciudad. Ver opciones del plugin (WooCommerce > Shipping > Chilexpress).
* Se evita mostrar "Sin Servicio" hasta que se completa dirección (billing o shipping). Ver [issue #179](https://github.com/whooohq/whq-woocommerce-chilexpress-shipping/issues/179).
* Se evita mostrar mensaje de Chilexpress no disponible (cuando cae la API de ellos), si el país no es Chile. Ver [issue #166](https://github.com/whooohq/whq-woocommerce-chilexpress-shipping/issues/166). Gracias @kikinangulo por el aviso.

### 1.4.43
* Resuelto problema de compatibilidad con WooCommerce 6.0. WooCommerce ahora incluye las regiones de Chile por defecto, sin necesidad de utilizar un plugin externo para ello. Ver [issue #228](https://github.com/whooohq/whq-woocommerce-chilexpress-shipping/issues/228). Gracias a @novusfusion por el aviso.
* Resuelto problema de datos guardados en Transients luego de actualización a 1.4.40 por cambio de nombres de regiones en WooCommerce 6.0. Ver [issue #230](https://github.com/whooohq/whq-woocommerce-chilexpress-shipping/issues/230). Gracias a @2xffwd por el aviso.

### 1.4.35
* Posibilidad de agregar un valor negativo al costo "extra" por embalaje en las opciones del plugin. ¿Descuentos?. Ver [issue #219](https://github.com/whooohq/whq-woocommerce-chilexpress-shipping/issues/219).
* Arreglado (nuevamente) el renombrado de métodos de envío. Ver [issue #209](https://github.com/whooohq/whq-woocommerce-chilexpress-shipping/issues/209). Gracias a @PatrickCaneloDigital por el fix.
* Agregado filtro al costo de cada tipo de envío (filtro: whq_wcchp_rates_cost). Ver [discussion #222](https://github.com/whooohq/whq-woocommerce-chilexpress-shipping/discussions/222).

### 1.4.30
* Arreglado el renombrado de métodos de envío, debido a cambio de nomenclatura usada por Chilexpress, desde el formato anterior "día hábil", "día hábil subsiguiente" y "tercer día" a nueva nomenclatura: "prioritario", "express" y "extendido".

### 1.4.29
* Agregada posibilidad de cambiar los nombres de los tipos de envío. Ver [issue #61](https://github.com/whooohq/whq-woocommerce-chilexpress-shipping/issues/61).
* Agregada posibilidad de color un costo extra por paquete al precio de envío. Ver [issue #108](https://github.com/whooohq/whq-woocommerce-chilexpress-shipping/issues/108).
* Resuelto problema en el carrito cuando solo existe un método de envío. Ver [issue #180](https://github.com/whooohq/whq-woocommerce-chilexpress-shipping/issues/180). Gracias @alvrsmp por el aviso.
* Actualizada lista de [plugins incompatibles](https://github.com/whooohq/whq-woocommerce-chilexpress-shipping/issues/18).

### 1.4.22
* Resuelto bug con WordPress Multisite. Ver [issue #71](https://github.com/whooohq/whq-woocommerce-chilexpress-shipping/issues/71#issuecomment-653269512). Gracias @LisetteHR por el aviso.
* Probada compatibilidad con WooCommerce 4.2

### 1.4.21
* Probada compatibilidad con WooCommerce 4.0

### 1.4.20
* Bugix: arreglo en caso de configuración no válida en tipos de envío soportados. Ver [issue #164](https://github.com/whooohq/whq-woocommerce-chilexpress-shipping/issues/164). Gracias @Fenomenoide21 por el aviso.

### 1.4.19
* Cambio de nombre requerido por el equipo de revisión de plugins de WordPress.

### 1.4.18

* Bugix: arreglo para tiendas que venden en más de un país. Ver [issue #151](https://github.com/whooohq/whq-woocommerce-chilexpress-shipping/issues/154). Gracias @garretonfco por el aviso.

### 1.4.17

* Bugix: arreglo para tiendas que fuerzan envíos a dirección de facturación. Ver [issue #151](https://github.com/whooohq/whq-woocommerce-chilexpress-shipping/issues/151). Gracias @juanitagonzalez por el aviso.

### 1.4.16

* Probada compatibilidad con WordPress 5
* Bugfix: arreglo de errores en PHP 7.2. Ver [issue #123](https://github.com/whooohq/whq-woocommerce-chilexpress-shipping/issues/123). Gracias @doronshe por el aviso.

### 1.4.13

* Bugfix: arreglo de URLs API Chilexpress QA. Gracias a @PatrickCaneloDigital.
* Mejora: posibilidad de seleccionar ambiente de uso de la API de Chilexpress. Ver [issue #114](https://github.com/whooohq/whq-woocommerce-chilexpress-shipping/issues/114). Agradecimientos por el aviso a @AndresReyesTech
* Mejora: compatibilidad con WordPress Network. Ver [issue #71](https://github.com/whooohq/whq-woocommerce-chilexpress-shipping/issues/71). Gracias a @odayan por el aviso.
* Mejora: posibilidad de activar cobro de impuestos por envío. Desactivado por default. Ver [issue #115](https://github.com/whooohq/whq-woocommerce-chilexpress-shipping/issues/115).

### 1.4.7

* Mejora: arreglo de compatibilidad con plugin [WooCommerce Chilean Peso](https://wordpress.org/plugins/woocommerce-chilean-peso-currency/), plugin que permite el uso de PayPal en Chile.
* Mejora: aviso de incompatibilidad ahora solo es mostrado en página de Plugins y configuración del Plugin. Ver [issue #103](https://github.com/whooohq/whq-woocommerce-chilexpress-shipping/issues/103).

### 1.4.5

* Mejora: compatibilidad con [plugin comercial](https://codecanyon.net/item/woocommerce-shipping-calculator-on-product-page/11496815) para mostrar calculadora de precios en página de producto. Opción recomendada para Shipping Method Input: Display All Shipping With Price (No compatible con opción "Radio"). Advertencia: no hay soporte, por nuestra parte, para el plugin de terceros.
* Mejora: filtros whq_wcchp_shipments_types y whq_wcchp_tarification_rates para cambiar nombres de tipos de envíos. Ver [issue #61](https://github.com/whooohq/whq-woocommerce-chilexpress-shipping/issues/61).

### 1.4.4

* Bugfix: resuelve la imposibilidad de pagar por un pedido pendiente desde la lista de pedidos. Ver [issue #104](https://github.com/whooohq/whq-woocommerce-chilexpress-shipping/issues/104). Muchas gracias @truchosky por el aviso ;)
* Mejora: posibilidad de sobre-escribir el tipo algoritmo utilizado para el cálculo del paquete. Agradecimientos a @PatrickCaneloDigital por el trabajo aquí.
* Mejora: posibilidad de sobre-escribir el origen de los paquetes. Ver [issue #97](https://github.com/whooohq/whq-woocommerce-chilexpress-shipping/issues/97).

### 1.3.14

* Bugfix: soluciona (otra vez) el problema de selección de tipos de envío soportados en la configuración del plugin. Ver [issue #100](https://github.com/whooohq/whq-woocommerce-chilexpress-shipping/issues/100). Muchas gracias a @PatrickCaneloDigital por reportar el error.

### 1.3.13

* Bugfix: soluciona el problema de selección de tipos de envío soportados en la configuración del plugin. Ver [issue #86](https://github.com/whooohq/whq-woocommerce-chilexpress-shipping/issues/86). Muchas gracias a @juanchomix por reportar el error.

### 1.3.12

* Bugfix: previene compras con Chilexpress con costo de envío cero, cuando API de Chilexpress no responde correctamente. Ver [issue #81](https://github.com/whooohq/whq-woocommerce-chilexpress-shipping/issues/81). Gracias @PatrickCaneloDigital por reportar el error!

### 1.3.10

* Doble checkeo de versión de PHP antes de función conflictiva.

### 1.3.9

* Demo público del plugin disponible en [WPChilexpress @ WhoooHQ](http://wpchilexpress.whooohq.com).
* Aclaración en el FAQ (Chilexpress caído, no disponible).

### 1.3.6

* Bugfix: corregido error en nuevo método de cálculo de paquetes introducido en v1.3.3. Gracias @PatrickCaneloDigital nuevamente.
* Fixed WooCommerce new version check header tags ([ref](https://woocommerce.wordpress.com/2017/08/28/new-version-check-in-woocommerce-3-2/)).

### 1.3.3

* Nuevo método de cálculo de paquetes disponible. Util para casos especiales (productos grandes). [Issue #44](https://github.com/whooohq/whq-woocommerce-chilexpress-shipping/issues/44). ¡Muchas gracias @PatrickCaneloDigital!
* En pausa soporte para Zonas de Envío. Bugs pendientes (configuración desde instancia específica v/s global).

### 1.3.1

* Nuevo: Implementada opción para utilizar Chilexpress dentro de las Zonas de Envío de WooCommerce [issue #39](https://github.com/whooohq/whq-woocommerce-chilexpress-shipping/issues/39).
* Nuevo: Agregadas Regiones de Chile para ser utilizadas cuando el plugin es utilizado en las Zonas de Envío.

### 1.2.6

* Igualada detección de PHP a algoritmo usado por WooCommerce. A ver si [#42](https://github.com/whooohq/whq-woocommerce-chilexpress-shipping/issues/42) no continua ocurriendo.
* Actualizado el FAQ. Referencia directa a [#27](https://github.com/whooohq/whq-woocommerce-chilexpress-shipping/issues/27#issuecomment-321929605) para futuros issues duplicados.
* SE BUSCAN opiniones en [issue #39](https://github.com/whooohq/whq-woocommerce-chilexpress-shipping/issues/39) respecto a un cambio sugerido en el plugin (Zonas de Envío). Desde ya, gracias por vuestra participación.

### 1.2.5

* Bugfix: arreglo de cierta condicion que permitía terminar una compra cuando Chilexpress está sin servicio.

### 1.2.4

* Bugfix: arreglo de auto-completado en los navegadores ([#34](https://github.com/whooohq/whq-woocommerce-chilexpress-shipping/issues/34)). Gracias @llermaly por el reporte.

### 1.2.3

* Mejoras en el carro de compras y el checkout ([PR#28](https://github.com/whooohq/whq-woocommerce-chilexpress-shipping/pull/28)). ¡Gracias @albetix!
* Limpiadas rutinas javascript del plugin. Agregado debug para desarrollo.
* Implementados headers para aviso de versión mínima de PHP oficial de WP ([ref.](https://make.wordpress.org/plugins/2017/08/29/minimum-php-version-requirement/)) y de WooCommerce ([ref.](https://woocommerce.wordpress.com/2017/08/28/new-version-check-in-woocommerce-3-2/)).
* Actualizada lista de plugins incompatibles ([#18](https://github.com/whooohq/whq-woocommerce-chilexpress-shipping/issues/18))

### 1.2.0

* Mejora: ahora puedes seleccionar los típos de envíos disponibles (Ultra Rápido, Overnight, Día hábil siguiente, Día hábil subsiguiente, Tercer día) que deseas soportar en tu tienda, configurables desde las opciones del método de envío ([#26](https://github.com/whooohq/whq-woocommerce-chilexpress-shipping/issues/26))

### 1.1.20

* Agregado nuevo plugin en lista de incompatibilidad ([#25](https://github.com/whooohq/whq-woocommerce-chilexpress-shipping/issues/25)).
* Mejora: utilizados placeholders de select2 (¡gracias @albetix por la idea!)

### 1.1.17

* Fix: https://github.com/whooohq/whq-woocommerce-chilexpress-shipping/issues/22
* Mejora: aumentado el tiempo de caché de las localidades a una semana mínimo, para evitar saturar la API de Chilexpress.
* Actualizada lista (hard-coded) de ciudades y localidades tanto como origen de envío y como para entregas.

### 1.1.13

* Mejora: https://github.com/whooohq/whq-woocommerce-chilexpress-shipping/issues/22
* Mejora: https://github.com/whooohq/whq-woocommerce-chilexpress-shipping/pull/23
* Mejorada limpieza de remanentes del plugin en caso de desinstalación.

### 1.1.9

* Mejora: calculadora recuerda valores registrados si eres cliente de la tienda (Gracias @albetix).
* Mejora: calculadora conserva valores de región y ciudad (Gracias @albetix).
* Mejora: calculadora pasa valores de región y ciudad al checkout (Gracias @albetix).
* Mejora: checkout pasa valores completos de región y ciudad a la órden de compra, y no los códigos de Chilexpress (Gracias @albetix).
* Fix: checkout ahora calcula independiente de dirección de facturación y/o envío  (Gracias @albetix).
* Fix: mejorada la detección de caida de API Chilexpress.

### 1.1.8

* Mejora: https://github.com/whooohq/whq-woocommerce-chilexpress-shipping/issues/15 ¡Gracias @llermaly y @albetix!

### 1.1.6

* Mejora: https://github.com/whooohq/whq-woocommerce-chilexpress-shipping/issues/18

### 1.1.5

* Fix: https://github.com/whooohq/whq-woocommerce-chilexpress-shipping/issues/17#issuecomment-316171410
* Mejora: caché de ubicaciones mínimo de un día.

### 1.1.3

* Fix: https://github.com/whooohq/whq-woocommerce-chilexpress-shipping/issues/14
* Fix: https://github.com/whooohq/whq-woocommerce-chilexpress-shipping/issues/13
* Fix: https://github.com/whooohq/whq-woocommerce-chilexpress-shipping/issues/11#issuecomment-315484722

### 1.1.0

* Mejora: implementado el cálculo de precios en la calculadora rápida del carro de compras. Ver: https://www.youtube.com/watch?v=JaLp1wmtKlk
* Mejora: caché de Regiones y Ciudades configurable. Ver opciones del plugin. Default 24 horas.
* Fix: https://github.com/whooohq/whq-woocommerce-chilexpress-shipping/issues/11

### 1.0.9

* Fix: https://github.com/whooohq/whq-woocommerce-chilexpress-shipping/issues/10
* Fix: https://github.com/whooohq/whq-woocommerce-chilexpress-shipping/issues/10#issuecomment-314546595

### 1.0.7

* Fix: https://github.com/whooohq/whq-woocommerce-chilexpress-shipping/issues/9

### 1.0.6

* Fix: https://github.com/whooohq/whq-woocommerce-chilexpress-shipping/issues/6
* Fix: https://github.com/whooohq/whq-woocommerce-chilexpress-shipping/issues/8

### 1.0.5

* Fix: https://github.com/whooohq/whq-woocommerce-chilexpress-shipping/issues/4

### 1.0.4

* Fix: Transient cache id
* Fix: Cache busting

### 1.0.2

* Implementado Select2 para los campos de región y ciudad/localidad.
* Otras mejoras y bugfixes.

### 1.0.1

* Primera versión pública.
