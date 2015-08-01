echo off
set PATH=%~dp0\..\..\bin;%~dp0\..\..\php;I:\leerlingen\xampp\php;C:\xampp\php;%PATH%
::echo on
::echo %PATH%
if exist composer.phar goto COMPOSER
goto END
:COMPOSER
php composer.phar self-update
php composer.phar update
:END
cmd