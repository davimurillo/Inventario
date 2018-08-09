  for /f "tokens=1-4 delims=/ " %%i in ("%date%") do (
     set dow=%%i
     set month=%%j
     set day=%%k
     set year=%%l
   )
   set datestr=%month%_%day%_%year%
   echo datestr is %datestr%

SET PG_DATABASE=INVENTARIO
SET FECHAYHORA=%date:/=%-%time:%
SET FECHAYHORA=%FECHAYHORA::=-%
SET FECHAYHORA=%FECHAYHORA: =0%
SET PG_FILENAME=D:\htdocs\db\%PG_DATABASE%-%FECHAYHORA%.backup

    
   set BACKUP_FILE=%PG_FILENAME%
   echo backup file name is %BACKUP_FILE%
   SET PGPASSWORD=ngr$$inventario
   echo on
   "C:\Program Files\PostgreSQL\9.5\bin\pg_dump.exe" -h localhost -p 5432 -U postgres -F c -b -v -f %BACKUP_FILE% "INVENTARIO"


