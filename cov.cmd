cls
echo off
set phpunitPhar=%~dp0/phpunit.phar
set PATH=%~dp0/../../../bin;%~dp0/../../php;%~d0/xampp/php;%PATH%

@php  %phpunitPhar% --coverage-html COVERAGE %CWD%Tests\%1 %2 %3 %4 %5 %6 %7 %8 %9

