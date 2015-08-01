@echo off
rem echo %cd%
rem echo %~dp0
set CWD=%~dp0
set AL=%~dp0src\autoload.php

cls

REM :: phpunit --bootstrap %AL% %CWD%tests\%*

if [%1] EQU []  goto NORMAL
set arg=%1
shift
if %arg% EQU ui goto UITESTS
if %arg% EQU unit goto UNITTESTS

::echo on
:NORMAL
php phpunit.phar  %CWD%Tests\%1 %2 %3 %4 %5 %6 %7 %8 %9
goto END
:UNITTESTS
php phpunit.phar  %CWD%Tests\%2 %3   %4 %5 %6 %7 %8 %9
goto END
:UITESTS
php phpunit.phar --bootstrap ./UITests/bootstrap.php  %CWD%UITests\%2 %3 %4 %5 %6 %7 %8 %9
goto END
:END
