CULabs Restaurant
========================

Esta aplicación fue desarrollada con el objetivo de participar en el concurso "http://muchomassymfony.com/". Además sirve de ejemplo para demostrar el uso del bundle CULbasAdminBundle.

Instalación
----------------------------------

### Instalar las dependencias

    php composer.phar install

Luego cree un enlace simbólico para el bootstrap

    ln -s /dir/app..../vendor/twitter/bootstrap/ /dir/app..../web/

### Crear la base de datos.
Para la base de datos del proyecto se utiliza sqlite. Luego crear la base de datos.

    php app/console doctrine:database:create
    php app/console doctrine:database:create -e=test

### Crear el esquema

    php app/console doctrine:schema:create
    php app/console doctrine:schema:create -e=test

### Correr fixtures
La aplicación viene con un cojunto de datos de pruebas que puede utilizar para ver el funcionamiento de la aplicacón para ello debe correr los fixtures.

    php app/console doctrine:fixtures:load

### Permisos a las carpetas
En cada caso quizás sea necesario dar los permisos correctos a las carpetas.

    app/cache
    app/logs
    app/data
    web/

Infomrción de contacto.
----------------------------------
Cualquier duda o sugerencia no dudar en contactar con el desarrollador Alejandro Pérez Cuba (aprezcuba24@gmail.com)