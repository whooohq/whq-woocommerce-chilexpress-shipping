Agradecemos cualquier ayuda reportando errores en el plugin, y también sugerencias y mejoras a implementar. Pero agradecemos aún más los Pull Requests (PR en adelante) con ayuda directa para resolver errores o mejorar el plugin.


# Al reportar un error (bugs)

Por favor, verifica que el error que encontraste no ocurra en una instalación de WordPress limpia, al día (WP actualizado, WooCommerce actualizado, nuestro plugin actualizado) y con un theme compatible con la última versión de WooCommerce (recomendamos [Storefront](https://woocommerce.com/storefront/) de WooCommerce, gratuito).

Antes de reportar tu error, [revisa el listado de plugins](https://github.com/whooohq/whq-woocommerce-chilexpress-shipping/issues/18) conocidos por incompatibilidad parcial o total con el nuestro, por favor.

Y busca. Utiliza la opción de búsqueda dentro de los Issues para ver que nadie ya haya reportado lo que estás intentando solucionar (ver Issues cerrados). Por favor. Te lo agradecemos un montón desde ya.


# Sugerencias

Son bienvenidas. Claro está, eso no nos obliga a aceptarlas todas, o implementarlas inmediatamente. Todo depende de varios factores.

Como por ejemplo, si la sugerencia hace sentido para una potencial mayoría de usuarios del plugin, y no solo tu caso en particular.

El tiempo. Se trabaja ad-honorem en este plugin, por lo que por muy genial que sea vuestra sugerencia, si implica mucho tiempo en implementarla, no necesariamente vuestra necesidad será satisfecha velozmente.

Si tu sugerecia viene acompañada de un Pull Request (resuelta por ti mismo), mejor aún ;D


# Como contribuir directamente al desarrollo del Plugin (Pull Requests)

Para aportar directamente (PR), deberás considerar:

* Todo aporte debe seguir los [estándares de código de WordPress](https://make.wordpress.org/core/handbook/best-practices/coding-standards/). Esto corre para PHP, HTML, CSS y Javascript. Se facilita un archivo [.editorconfig](http://editorconfig.org/) para seguir los estándares de WordPress; si tu editor lo soporta, por favor, úsalo. Si te solicitamos mejorar algún punto de un PR en torno a los estándares de desarrollo de WP, por favor, no tomar a mal. Es por el bien de todos (usuarios y quienes aportarán) a futuro.
* Si vas a incluir una librería de terceros, deberás utilizar [Composer](http://getcomposer.org/) para PHP, y [Bower](https://bower.io/) para CSS y JS (por ahora; no necesitamos NPM ni Yarn u otros como Gulp o Webpack por ahora). Mientras hoy en el plugin no existe uso de librerías de terceros, sugerencias para archivos de configuración de Composer y Bower son bienvenidas.
* Para toda librería de tercero (PHP, CSS o JS) la licencia de aquella librería deberá ser compatible con la licencia GPLv2 (tal como WordPress).
* Si vas a usar snippets de código de terceros, por ejemplo, desde GitHub (Gists) o StackOverflow, por favor entrega el crédito correspondiente al creador del snippet de código. Con un comentario (//) sobre el snippet de código y la URL de dónde el autor original publicó dicho snippet. En el caso de Stackoverflow [la atribución es requerida](https://meta.stackexchange.com/questions/272956/a-new-code-license-the-mit-this-time-with-attribution-required) mediante una URL al post donde se encontraba el snippet.
* Programa en inglés. Todo nombre de variable, función, clase, método, etc. deberá ser claro (por sobre el abreviado), siguiendo los [estándares de WordPress](https://make.wordpress.org/core/handbook/best-practices/coding-standards/php/#naming-conventions) y escrito en inglés. Esto es para todo, excepto los textos visibles para el usuario en el front-end (por ahora no ofrecemos traducciones en el plugin; si a futuro lo hacemos, esta regla se extenderá).
* Comenta tu código. Nos ayuda mucho, a todos, a entender que estás tratando de hacer. Comentarios simples (//), multi-lineas y/o docBlocks (/** */) si es pertinente. Si tu código resuelve un issue en el repositorio del plugin, por favor incluye un comentario con la URL del issue que estás resolviendo en el fix que enviarás como PR (donde sea conveniente para saber que ese cambio es por un issue específico). Intenta escribir los comentarios en inglés (si fuese posible; no es mandatorio).
* Haber probado tus cambios en una instalación limpia de WordPress y actualizada, con el [modo Debug activado](https://codex.wordpress.org/Debugging_in_WordPress), con WooCommerce instalado y actualizado al más reciente, con el theme [Storefront](https://woocommerce.com/storefront/) de WooCommerce instalado, actualizado y activado (theme gratuito), y con el plugin nuestro instalado y actualizado a la versión más reciente (recomendado clonar el [repositorio](https://github.com/whooohq/whq-woocommerce-chilexpress-shipping) directo en la carpeta plugins de WordPress). En Windows y macOS puedes ayudarte de [Local by Flywheel](https://local.getflywheel.com/) para instalar un ambiente de desarrollo de manera sencilla.

Si tienes sugerencias respecto a este código para las contribuciones al plugin, por favor, abre un nuevo issue al respecto, y conversemos ;)

¡Gracias desde ya por vuestro tiempo y ayuda!
