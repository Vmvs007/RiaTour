# Planeamento de execução — 6 horas

Tempos líquidos nas três etapas da prova. Os intervalos, o almoço e o briefing ficam fora das seis horas. Os minutos abaixo contam desde o início de cada módulo. Ver a [matriz CIS](03-MATRIZ-CIS.md) para os testes e os [requisitos](01-REQUISITOS.md) para os nomes de entrega e as ambiguidades.

| Módulo | Tempo | Acumulado | Mínimo de fecho |
| --- | ---: | ---: | --- |
| 1 — Base de dados e SQL | 90 min | 1 h 30 | Modelo legível, base importável com os mínimos de dados, seis consultas verificadas e ZIP. |
| 2 — Análise e desenho | 45 min | 2 h 15 | Dois atores e respetivos casos, fluxo de reserva com sucesso e erro, cinco wireframes mínimos e ZIP. |
| 3 — Aplicação funcional | 225 min | 6 h | Cinco áreas ligadas ao MySQL com CRUD, detalhe, pesquisas, validações e mensagens essenciais. ZIP executável seguindo as instruções. |
| **Total** | **360 min** | **6 h** | **Três ZIP verificados.** |

## Módulo 1 · 90 minutos

| Minutos | Trabalho | Mínimo ao terminar |
| --- | --- | --- |
| 00–20 | Identificar entidades, atributos, chaves, relações e cardinalidades. Desenhar o modelo. | Cinco entidades no modelo, com ligações legíveis. |
| 20–45 | Criar a base MySQL, tabelas, tipos e restrições. | Estrutura importável e teste de referência inválida. |
| 45–60 | Inserir dados que permitam verificar as consultas e reutilizar os formulários no M3. | 2 barcos, 2 percursos, 2 clientes, 3 horários e 3 reservas válidos. |
| 60–80 | Escrever e correr as seis consultas. | Seis resultados comparados com os dados de exemplo, incluindo totais e filtros. |
| 80–90 | Exportar, testar a importação e fechar a entrega. | ZIP aberto e validado. Guardar cópia local da base e do trabalho. |

## Módulo 2 · 45 minutos

| Minutos | Trabalho | Mínimo ao terminar |
| --- | --- | --- |
| 00–10 | Desenhar os atores e as ligações aos casos de uso. | Operador, Cliente e todas as ações explicitadas. |
| 10–20 | Desenhar o fluxo de criação de reserva. | Seleções e quantidade, decisão de disponibilidade, registo e mensagens de sucesso ou erro. |
| 20–40 | Desenhar os cinco wireframes mínimos e anotar a navegação. | Inicial, barcos, percursos, horários e reservas com os campos e ações pedidos. Clientes acessível no menu. |
| 40–45 | Conferir coerência, exportar e fechar o ZIP. | Três documentos legíveis e arquivo conferido. |

## Módulo 3 · 225 minutos

O Guia reparte este módulo em 60, 120 e 45 minutos. Os três blocos abaixo seguem essa distribuição. A primeira área serve de exemplo para reutilizar menu, formulários e padrões de acesso a dados nas seguintes.

| Minutos | Trabalho | Mínimo ao terminar |
| --- | --- | --- |
| 000–015 | Importar a base do M1, confirmar PHP/PDO e Bootstrap local, montar menu e layout. | Página inicial abre, os cinco destinos estão identificados e a aplicação lê dados MySQL. |
| 015–040 | Completar a gestão de barcos com listagem, detalhe e alterações. | CRUD de barcos persistente com validação da capacidade, campos obrigatórios e proteção das alterações. |
| 040–060 | Reutilizar o padrão nos percursos e preparar cópia local do primeiro bloco. | CRUD e detalhe de percursos, com duração e preço positivos. |
| 060–085 | Implementar clientes. | CRUD e detalhe de clientes, com contactos apresentados. |
| 085–115 | Implementar passeios, referências e validação da data. | CRUD e detalhe de passeios com barco e percurso identificáveis. |
| 115–160 | Implementar reservas, alteração de estado e validação da lotação. | CRUD e detalhe de reservas. A regra de lotação funciona ao criar ou alterar e permite atingir exatamente a capacidade. |
| 160–180 | Ligar campos de pesquisa e filtros nas cinco áreas. | Cada campo pedido tem um caso com resultado confirmado. Guardar cópia local do segundo bloco. |
| 180–200 | Testar o percurso completo, entradas inválidas, mensagens e integridade. | Uma execução de CRUD por entidade. Confirmar ausência de registos órfãos e verificar lotação e datas nos limites. |
| 200–210 | Corrigir falhas que impeçam a utilização e completar a consistência visual. | Navegação, tabelas, formulários e mensagens legíveis. |
| 210–225 | Escrever instruções TXT, exportar a base se alterada e validar o ZIP a partir de uma extração limpa. | Código-fonte e instruções presentes. A aplicação abre com a base indicada e permite consultar e guardar dados. |

## Organização do trabalho

- Usar MySQL com Workbench ou outra ferramenta disponível no posto para importar e verificar a base. Confirmar no arranque o acesso a MySQL, PHP com PDO, navegador, ferramenta de desenho e compactação.
- Usar nomes consistentes, manter cópias locais durante o desenvolvimento e verificar a recuperação de uma cópia se necessário. A lista de infraestruturas proíbe trazer pen drives e discos externos.
- Se o tempo apertar, usar componentes gráficos simples e reutilizar as mesmas estruturas de tabela e formulário. Manter a validação e a integridade desde a primeira implementação de cada área. Reservar os minutos de fecho para testar a entrega. As pesquisas e a revisão de UI/UX continuam previstas no módulo.
- Estrutura proposta para a implementação posterior: `database/` para exportação e consultas, `design/` para os diagramas e wireframes, `app/` para o código modular e os recursos locais, `docs/` para documentação. Manter os ZIP de entrega fora das pastas de trabalho que serão compactadas.
