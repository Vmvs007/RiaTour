# RiaTour

Projeto pedagógico para a WorldSkills Portugal Centro 2027, profissão 09. A aplicação base permite navegar e consultar barcos, percursos, passeios, clientes e reservas. O dashboard consulta os barcos ativos na base do Módulo 1.

## Executar localmente

Requer PHP 8.1 ou posterior com PDO MySQL e mbstring, e MySQL 8.0.16 ou posterior. O XAMPP disponibiliza MariaDB, usado nos testes locais deste projeto. Bootstrap 5.3.3 está incluído em public/assets/bootstrap e funciona sem internet.

1. Em XAMPP, copiar a pasta do projeto para C:/xampp/htdocs/riatour. Em Laragon, usar C:/laragon/www/riatour.
2. Iniciar Apache e o servidor de base de dados no painel do XAMPP ou Laragon.
3. Importar database/01_schema.sql e depois database/02_seed.sql numa base nova, usando o cliente SQL ou o phpMyAdmin. A base criada chama-se bdRiaTour_NomeConcorrente_C27. O ficheiro 03_queries.sql contém as consultas didáticas.
4. Editar app/config/local.php com o endereço, porta, nome da base, utilizador e palavra-passe locais. A configuração inicial usa 127.0.0.1, porta 3306 e root sem palavra-passe. Ajustar aos valores do posto.
5. Definir base_url como /riatour/public e abrir http://localhost/riatour/public/ no navegador.

Para usar um virtual host, apontar a raiz do site para public/ e definir base_url como uma string vazia. Assim, apenas os ficheiros públicos são servidos pelo site.

Também é possível iniciar o servidor de desenvolvimento a partir da pasta do projeto, com base_url vazio e o servidor de base de dados já iniciado.

```powershell
C:/xampp/php/php.exe -S 127.0.0.1:8080 -t public
```

Abrir http://127.0.0.1:8080/. Em Laragon, executar php no terminal disponibilizado pelo programa.

## Organização

- app/config/local.php guarda a configuração do posto.
- app/bootstrap.php inicia a sessão, carrega configuração e funções e define o menu.
- app/helpers/functions.php contém PDO, consultas preparadas, escape HTML, mensagens de sessão e funções para validar POST e CSRF.
- app/views/ contém header, footer e a tabela reutilizável.
- public/index.php apresenta o dashboard.
- public/boats, routes, trips, customers e bookings contêm as páginas de consulta.
- public/assets/bootstrap contém CSS e JavaScript locais do Bootstrap.
- database/ contém o modelo e os exemplos do Módulo 1.

As consultas SQL são definidas nos ficheiros PHP. Os valores variáveis usam parâmetros PDO, como o estado do barco na página inicial. Toda a saída dinâmica é escapada com e(). As mensagens são consumidas pelo header e os erros técnicos são enviados para o registo de erros do PHP.

As cinco áreas incluem criação, listagem, detalhe, edição, eliminação confirmada e pesquisa. As alterações usam POST com CSRF e validação no servidor. Passeios e reservas usam dropdowns e transações com bloqueio para proteger a lotação. A [cábula CRUD](docs/07-CABULA-CRUD.md) explica os exemplos iniciais e a [cábula de reservas](docs/08-CABULA-RELACOES-E-RESERVAS.md) descreve as relações e a concorrência.

## Verificação

Com os dados de exemplo, o dashboard deve mostrar Ria Serena em Barcos ativos. O menu deve abrir as cinco listagens, incluindo os nomes dos clientes, barcos e percursos associados. Se o servidor estiver parado ou a configuração estiver incorreta, a página apresenta uma mensagem de erro e responde com HTTP 503.

Foram verificados a sintaxe dos 12 ficheiros PHP, o acesso HTTP às seis páginas e aos dois recursos Bootstrap, a consulta real do dashboard através de PDO e a resposta de indisponibilidade. O ensaio utilizou PHP 8.2.12 e MariaDB 10.4.32 numa base temporária com os dados do Módulo 1. A execução num servidor MySQL permanece por verificar.
