# Como contribuir al desarrollo del Plugin

Agradecemos cualquier ayuda reportando errores en el plugin, y también sugerencias y mejoras a implementar. Pero agradecemos aún más los Pull Requests (PR en adelante) con ayuda directa para resolver errores o mejorar el plugin.

Si te solicitamos mejorar algún punto de un PR en torno a los estándares, por favor, no tomar a mal. Es por el bien de todos (usuarios y quienes aportarán) a futuro.

Para aportar directamente (PR), deberás considerar:

* Todo aporte debe seguir los [estándares de código de WordPress](https://make.wordpress.org/core/handbook/best-practices/coding-standards/). Esto corre para PHP, HTML, CSS y Javascript. Se facilita un archivo [.editorconfig](http://editorconfig.org/) para seguir los estándares de WordPress; si tu editor lo soporta, por favor, úsalo.
* Si vas a incluir una librería de terceros, deberás utilizar [Composer](http://getcomposer.org/) para PHP, y [Bower](https://bower.io/) para CSS y JS (por ahora; no necesitamos NPM ni Yarn u otros como Gulp o Webpack por ahora). Mientras hoy en el plugin no existe uso de librerías de terceros, sugerencias para archivos de configuración de Composer y Bower son bienvenidas.
* Para toda librería de tercero (PHP, CSS o JS) la licencia de aquella librería deberá ser compatible con la licencia GPLv2 (tal como WordPress).
* Si vas a usar snippets de código de terceros, por ejemplo, desde GitHub (Gists) o StackOverflow, por favor entrega el crédito correspondiente al creador del snippet de código. Por ejemplo, con un comentario // sobre el snippet de código y la URL de dónde el autor original publicó dicho snippet. En el caso de Stackoverflow [la atribución es requerida](https://meta.stackexchange.com/questions/272956/a-new-code-license-the-mit-this-time-with-attribution-required) mediante una URL al post donde se encontraba el snippet.
* Programa en inglés. Todo nombre de variable, función, clase, método, etc. deberá ser claro (por sobre el abreviado), siguiendo los [estándares de WordPress](https://make.wordpress.org/core/handbook/best-practices/coding-standards/php/#naming-conventions) y escrito en inglés. Esto es para todo, excepto los textos visibles para el usuario en el front-end (por ahora no ofrecemos traducciones en el plugin; si a futuro lo hacemos, esta regla se extenderá).
* Haber probado tus cambios en una instalación limpia de WordPress y actualizada, con el [modo Debug activado](https://codex.wordpress.org/Debugging_in_WordPress), con WooCommerce instalado y actualizado al más reciente, con el theme [Storefront](https://woocommerce.com/storefront/) de WooCommerce instalado, actualizado y activado (theme gratuito), y con el plugin nuestro instalado y actualizado a la versión más reciente (recomendado clonar el [repositorio](https://github.com/whooohq/whq-woocommerce-chilexpress-shipping) directo en la carpeta plugins de WordPress). En Windows y macOS puedes ayudarte de [Local by Flywheel](https://local.getflywheel.com/) para instalar un ambiente de desarrollo de manera sencilla.

Si tienes sugerencias respecto a este código para las contribuciones al plugin, por favor, abre un nuevo issue al respecto, y conversemos ;)
