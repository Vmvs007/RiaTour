RiaTour - Execução
Requer PHP 8.1+, PDO MySQL, mbstring e MySQL 8.0.16+.
Testado em MariaDB 10.4.32. Confirmar MySQL no posto.
Importar DB_RiaTour_NomeConcorrente_M3.sql numa base nova.
Ajustar app/config/local.php às credenciais locais.
Executar php -S 127.0.0.1:8080 -t public e abrir http://127.0.0.1:8080/
XAMPP: usar C:/xampp/php/php.exe. Laragon: usar o terminal do programa.
Para Apache na subpasta riatour, definir base_url como /riatour/public.
Bootstrap local. Criar barco, percurso e cliente antes de passeio e reserva.
Pendentes e confirmadas ocupam lugares. Canceladas libertam lugares.
O SQL reconstitui o esquema e os dados exemplificativos do projeto.
