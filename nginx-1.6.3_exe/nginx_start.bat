@echo off

echo Starting PHP FastCGI...

f:
cd F:\Server\php\

start php-cgi.exe -b 127.0.0.1:9000 -c php.ini


echo Starting nginx...
cd\
cd F:\Server\nginx-1.6.3_exe
start nginx.exe

pause

@echo off

echo Stopping nginx...

taskkill /F /IM nginx.exe > nul

echo Stopping PHP FastCGI...

taskkill /F /IM php-cgi.exe > nul

exit


