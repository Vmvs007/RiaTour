# Matriz de requisitos e avaliação CIS

O identificador `C2.M7` designa o aspeto M7 do subcritério C2. Os valores são máximos oficiais por aspeto. Quando o mesmo código surge em várias linhas, a pontuação é partilhada. Por exemplo, as seis consultas contribuem em conjunto para os 2,00 pontos de C2.M7. A ligação entre requisitos e aspetos é uma interpretação pedagógica da grelha. Os testes são verificações propostas para a implementação futura.

**P1** — Concluir no primeiro percurso funcional ou no fecho obrigatório do módulo. **P2** — Completar depois da base funcional, dentro do tempo do módulo. Ambas incluem requisitos obrigatórios. A prioridade indica ordem de execução.

Ver [requisitos e ambiguidades](01-REQUISITOS.md) e [planeamento](02-PLANEAMENTO.md).

## Módulo 1 — Base de dados relacional e SQL

| ID | Requisito | Aspeto CIS | Pontos máximos na mesma ordem | Componente responsável | Como testar | Prioridade |
| --- | --- | --- | --- | --- | --- | --- |
| M1-01 | Identificar as cinco entidades: barco, percurso, passeio (horário), cliente e reserva. | C1.M1 | 2,00 | Modelo relacional | Localizar as cinco entidades no diagrama. | P1 |
| M1-02 | Representar atributos, chaves primárias e chaves estrangeiras. | C1.M2, C1.M3, C1.M5 | 1,50 / 1,50 / 1,50 | Modelo relacional | Conferir os atributos da ficha de dados e seguir cada referência até à respetiva chave. | P1 |
| M1-03 | Representar relações e cardinalidades num modelo normalizado e legível. | C1.M4, C1.M6, C1.M7 | 2,00 / 1,50 / 1,00 | Modelo relacional | Seguir barco e percurso até passeio, e cliente e passeio até reserva. Verificar que os dados de origem aparecem uma vez. | P1 |
| M1-04 | Criar em MySQL a base bdRiaTour_NomeConcorrente_C27 e as tabelas do modelo. | C2.M1, C2.M8 | 2,00 / 1,00 | Exportação SQL | Importar num servidor de teste e confirmar o nome, as cinco tabelas e a ausência de erros críticos. | P1 |
| M1-05 | Implementar chaves primárias e estrangeiras, tipos adequados e restrições de integridade aplicáveis. | C2.M2, C2.M3, C2.M4, C2.M5 | 1,50 / 1,50 / 1,50 / 1,00 | Estrutura SQL | Inspecionar as definições. Tentar inserir uma chave duplicada, uma referência inexistente e valores inválidos abrangidos pelas restrições. | P1 |
| M1-06 | Inserir exemplos em todas as tabelas, com pelo menos 2 barcos, 2 percursos, 2 clientes, 3 horários e 3 reservas. | C2.M6 | 1,50 | Dados da exportação SQL | Contar linhas por tabela após importar e conferir as referências. | P1 |
| M1-Q1 | Listar todos os barcos ativos. | C2.M7 | 2,00 | Consultas SQL | Usar um barco ativo e um indisponível. Esperar apenas o ativo. | P1 |
| M1-Q2 | Listar todos os percursos ordenados pelo nome. | C2.M7 | 2,00 | Consultas SQL | Inserir dois nomes fora da ordem alfabética e confirmar a ordem pelo nome. | P1 |
| M1-Q3 | Listar todos os horários disponíveis com os nomes do barco e do percurso. | C2.M7 | 2,00 | Consultas SQL | Comparar horários em estados distintos e conferir ambos os nomes em cada linha devolvida. | P1 |
| M1-Q4 | Listar todas as reservas com nome do cliente, percurso, data e hora do passeio. | C2.M7 | 2,00 | Consultas SQL | Conferir o número de reservas, incluindo estados distintos, e distinguir data do passeio de data da reserva. | P1 |
| M1-Q5 | Calcular o total de passageiros reservados por horário. | C2.M7 | 2,00 | Consultas SQL | Somar dois registos do mesmo horário e conferir outro horário separadamente. Usar o tratamento dos estados e dos horários vazios documentado. | P1 |
| M1-Q6 | Apresentar os horários de um barco escolhido. | C2.M7 | 2,00 | Consultas SQL | Selecionar um barco e confirmar que os horários do outro ficam excluídos. | P1 |
| M1-07 | Entregar o ZIP do M1 com exportação e dados, modelo e as seis consultas incluídas ou num ficheiro próprio. | A1.M2, A2.M2 | 1,50 / 1,00 | WSC27_NomeConcorrente_M1.zip | Abrir o ZIP, verificar os nomes indicados nos requisitos e executar a importação e as consultas da entrega. | P1 |

## Módulo 2 — Análise e desenho da aplicação

| ID | Requisito | Aspeto CIS | Pontos máximos na mesma ordem | Componente responsável | Como testar | Prioridade |
| --- | --- | --- | --- | --- | --- | --- |
| M2-01 | Representar Operador e Cliente e ligar cada ator aos seus casos de uso. | D1.M1, D1.M3 | 1,00 / 1,00 | UC_RiaTour_NomeConcorrente_M2.pdf | Conferir as associações com a lista de ações em 01-REQUISITOS.md. | P1 |
| M2-02 | Representar as cinco áreas de gestão, a consulta e alteração de estado pelo Operador e a consulta, reserva e cancelamento pelo Cliente. | D1.M2 | 2,00 | Diagrama de casos de uso | Verificar as dez ações explícitas do enunciado. | P1 |
| M2-03 | Desenhar a criação de reserva da seleção do passeio à confirmação ou erro, com seleção/inserção do cliente, passageiros, disponibilidade e registo. | D2.M1, D2.M2 | 1,50 / 1,50 | FLUXO_RiaTour_NomeConcorrente_M2.pdf | Percorrer os ramos com e sem disponibilidade. Confirmar que o ramo de erro termina sem registo e que a decisão é legível. | P1 |
| M2-04 | Desenhar o ecrã inicial com menu e acesso às cinco áreas de gestão. | D3.M1, D3.M3 | 2,00 / 1,50 | Wireframe inicial | Seguir os cinco destinos, incluindo Clientes. | P1 |
| M2-05 | Desenhar lista de barcos com nome, capacidade e estado, e ações de criar, editar e eliminar. | D3.M1 | 2,00 | Wireframe de barcos | Conferir as três colunas e as três ações. | P1 |
| M2-06 | Desenhar lista de percursos com nome, duração e preço base, e ações de criar, editar e eliminar. | D3.M1 | 2,00 | Wireframe de percursos | Conferir as três colunas e as três ações. | P1 |
| M2-07 | Desenhar lista de horários com barco, percurso, data, hora e estado, e ações de criar, editar e eliminar. | D3.M1 | 2,00 | Wireframe de horários | Conferir as cinco colunas e as três ações. | P1 |
| M2-08 | Desenhar lista de reservas com cliente, percurso, barco, data e hora do passeio, passageiros e estado, e ações de criar, editar e cancelar ou eliminar. | D3.M1 | 2,00 | Wireframe de reservas | Conferir campos e ações. Seguir a criação até ao formulário previsto no fluxo. | P1 |
| M2-09 | Organizar wireframes e formulários de forma legível e coerente com o fluxo e os casos de uso. | D3.M2, D3.M3 | 1,50 / 1,50 | Conjunto de wireframes | Simular o percurso do menu à reserva. Conferir campos, ações e retorno à listagem. | P2 |
| M2-10 | Entregar o ZIP do M2 com diagrama, fluxograma e wireframes. | A1.M2, A2.M2 | 1,50 / 1,00 | WSC27_NomeConcorrente_M2.zip | Abrir os três documentos e confirmar nomes, conteúdo e legibilidade. | P1 |

## Módulo 3 — Gestão de registos e manipulação de dados

| ID | Requisito | Aspeto CIS | Pontos máximos na mesma ordem | Componente responsável | Como testar | Prioridade |
| --- | --- | --- | --- | --- | --- | --- |
| M3-01 | Implementar ecrã inicial com navegação para Barcos, Percursos, Passeios, Clientes e Reservas. | E1.M1, E1.M3, F2.M1 | 2,00 / 2,00 / 2,00 | Ecrã inicial e menu comum | Abrir os cinco destinos e regressar ao início. | P1 |
| M3-02 | Ligar a aplicação por PDO ao MySQL e reutilizar a base do M1. | E3.M1, E5.M2 | 2,00 / 2,00 | Ligação MySQL | Abrir a aplicação após importar a base do M1 e confirmar que lê os exemplos. | P1 |
| M3-03 | Apresentar identificador, nome, capacidade máxima de passageiros e estado do barco. | E1.M1, E1.M2 | 2,00 / 2,00 | Gestão de barcos | Conferir campos de formulário e de consulta com um barco existente. | P1 |
| M3-04 | Apresentar identificador, nome, descrição, duração estimada e preço por passageiro do percurso. | E1.M1, E1.M2 | 2,00 / 2,00 | Gestão de percursos | Conferir o detalhe e os campos editáveis do percurso. | P1 |
| M3-05 | Apresentar identificador, barco, percurso, data, hora e estado do passeio. | E1.M1, E1.M2, F3.M2 | 2,00 / 2,00 / 2,00 | Gestão de passeios | Conferir data, hora e nomes das referências na interface. | P1 |
| M3-06 | Apresentar identificador, nome, email e telefone do cliente. | E1.M1, E1.M2 | 2,00 / 2,00 | Gestão de clientes | Conferir os dados apresentados e os campos de edição. | P1 |
| M3-07 | Apresentar identificador, cliente, passeio, número de passageiros, data da reserva e estado. | E1.M1, E1.M2, F3.M2 | 2,00 / 2,00 / 2,00 | Gestão de reservas | Conferir os seis elementos e se o cliente e o passeio são identificáveis. | P1 |
| M3-08 | Criar registos nas cinco áreas e persistir as referências válidas. | E2.M1, E3.M2 | 2,00 / 2,00 | Formulários de criação | Criar um registo por área em ordem de dependências e conferir na base. | P1 |
| M3-09 | Listar e visualizar individualmente registos nas cinco áreas. | E2.M2, E3.M1 | 2,00 / 2,00 | Listagens e detalhe | Importar os exemplos e abrir o detalhe de um registo de cada área. | P1 |
| M3-10 | Editar registos nas cinco áreas e persistir as alterações. | E2.M3, E3.M3 | 2,00 / 2,00 | Formulários de edição | Alterar um valor em cada área, voltar a abrir e conferir na base. | P1 |
| M3-11 | Eliminar registos nas cinco áreas, preservando as relações. | E2.M4, E3.M3 | 2,00 / 2,00 | Ações de eliminação | Eliminar um registo descartável por área. Tentar eliminar um registo referenciado e confirmar que não ficam órfãos. | P1 |
| M3-12 | Manter o CRUD consistente e apresentar referências por nomes ou descrições quando possível. | E2.M5, E3.M2, E3.M3, F3.M2 | 2,00 / 2,00 / 2,00 / 2,00 | Cinco áreas de gestão | Executar o ciclo completo e comparar detalhe, listagem e base de dados. | P1 |
| M3-13 | Alterar o estado de uma reserva. | E2.M3, E4.M2 | 2,00 / 2,00 | Gestão de reservas | Mudar um estado válido e confirmar a atualização e a aplicação da regra de lotação. | P1 |
| M3-14 | Pesquisar barcos por nome ou estado. | E3.M4 | 2,00 | Filtros de barcos | Testar cada campo com correspondência e sem correspondência. | P2 |
| M3-15 | Pesquisar percursos por nome. | E3.M4 | 2,00 | Pesquisa de percursos | Procurar um nome existente e outro ausente. | P2 |
| M3-16 | Pesquisar passeios por data, barco ou percurso. | E3.M4 | 2,00 | Filtros de passeios | Testar os três filtros individualmente com dados distintos. | P2 |
| M3-17 | Pesquisar clientes por nome ou email. | E3.M4 | 2,00 | Pesquisa de clientes | Testar cada campo com um valor existente e outro ausente. | P2 |
| M3-18 | Pesquisar reservas por cliente, passeio ou estado. | E3.M4 | 2,00 | Filtros de reservas | Testar os três filtros individualmente e confirmar os registos devolvidos. | P2 |
| M3-19 | Rejeitar campos obrigatórios vazios no servidor. | E4.M1, E4.M3 | 1,50 / 1,50 | Validação dos formulários | Enviar um campo obrigatório vazio em cada área e confirmar mensagem e ausência de gravação. | P1 |
| M3-20 | Exigir capacidade máxima do barco superior a zero. | E4.M2 | 2,00 | Validação de barcos | Tentar gravar 0 e um valor negativo. Aceitar um inteiro positivo válido. | P1 |
| M3-21 | Exigir duração do percurso superior a zero. | E4.M2 | 2,00 | Validação de percursos | Tentar gravar 0 e um valor negativo. Aceitar uma duração positiva válida. | P1 |
| M3-22 | Exigir preço por passageiro superior a zero. | E4.M2 | 2,00 | Validação de percursos | Tentar gravar 0 e um valor negativo. Aceitar um preço decimal positivo válido. | P1 |
| M3-23 | Exigir número de passageiros da reserva superior a zero. | E4.M2 | 2,00 | Validação de reservas | Tentar gravar 0 e um valor negativo. Aceitar um inteiro positivo dentro da lotação. | P1 |
| M3-24 | Impedir agendar um passeio em data anterior à data atual. | E4.M2 | 2,00 | Validação de passeios | Tentar gravar com a data de ontem e confirmar que a data de hoje passa nesta regra. | P1 |
| M3-25 | Impedir que o total de passageiros reservados para o passeio exceda a capacidade do barco. | E4.M2, E2.M5 | 2,00 / 2,00 | Validação de lotação | Com capacidade 10 e 8 lugares ocupados, aceitar 2 e rejeitar mais 1. Editar a própria reserva e verificar que os seus lugares são contados uma vez. Testar mudança de estado, troca de barco e redução da capacidade quando afetem a lotação. | P1 |
| M3-26 | Organizar tabelas, formulários e controlos, mantendo a navegação e apresentação coerentes e as ações identificadas. | E1.M4, F1.M1, F1.M2, F1.M3, F2.M1, F2.M2, F2.M3, F3.M2, F3.M3 | 2,00 / 2,00 / 2,00 / 2,00 / 2,00 / 2,00 / 2,00 / 2,00 / 2,00 | Layout comum e cinco áreas | Percorrer as áreas e verificar rótulos, leitura das tabelas, alinhamento e facilidade de localizar ações. | P2 |
| M3-27 | Apresentar mensagens de sucesso, erro e confirmação. | E4.M3, F3.M1 | 1,50 / 2,00 | Formulários e ações | Guardar dados válidos, enviar dados inválidos e confirmar/cancelar uma eliminação. | P2 |
| M3-28 | Aplicar as regras do AGENTS.md, incluindo consultas preparadas, escape de HTML, validação no servidor e CSRF nas alterações. | B1.M2, E5.M1 | 2,00 / 2,00 | Acesso a dados, formulários e ações | Rever a parametrização, a saída HTML e a proteção CSRF. Conferir organização, stack local e convenções de comentários. | P1 |
| M3-29 | Entregar ZIP com código-fonte, instruções de execução e exportação da BD se alterada durante o desenvolvimento. | A1.M2, A2.M2, E5.M2 | 1,50 / 1,00 / 2,00 | WSC27_NomeConcorrente_M3.zip | Extrair numa pasta limpa e seguir as instruções até abrir e testar a aplicação. | P1 |

## Aspetos transversais

| ID | Requisito | Aspeto CIS | Pontos máximos na mesma ordem | Componente responsável | Como testar | Prioridade |
| --- | --- | --- | --- | --- | --- | --- |
| T-01 | Sequenciar tarefas e interpretar o enunciado antes de executar. | A1.M1, B1.M1 | 1,50 / 2,00 | Planeamento e requisitos | Confirmar dependências e confrontar a cobertura com as secções dos três módulos. | P1 |
| T-02 | Organizar ficheiros e recursos, usar nomes consistentes e fazer backups durante o desenvolvimento. | A2.M1, A2.M2, A2.M3 | 1,00 / 1,00 / 1,00 | Pastas de trabalho e cópias locais | Confirmar estrutura, nomes e existência de uma cópia recuperável criada durante o trabalho. | P1 |
| T-03 | Trabalhar de forma autónoma e organizada, com postura profissional. | B2.M1, B2.M2 | 1,00 / 1,00 | Execução observada durante a prova | Observar autonomia, organização e postura no posto de trabalho. | P1 |

## Pontuação por subcritério

A sequência das páginas do PDF CIS corresponde aos subcritérios na ordem abaixo. A coluna de requisitos indica onde observar evidência para cada aspeto. O agrupamento por módulo é uma leitura do âmbito da grelha.

| Âmbito | Subcritérios | Pontos |
| --- | --- | ---: |
| Transversal | A1, A2, B1, B2 | 12,00 |
| Módulo 1 | C1, C2 | 23,00 |
| Módulo 2 | D1, D2, D3 | 12,00 |
| Módulo 3 | E1 a E5, F1 a F3 | 53,00 |
| **Total** | | **100,00** |

| Subcritério | Descrição | Máximo |
| --- | --- | ---: |
| A1 | Planeamento da execução | 3,00 |
| A2 | Organização do trabalho | 3,00 |
| B1 | Interpretação dos requisitos | 4,00 |
| B2 | Autonomia na execução | 2,00 |
| C1 | Modelação de dados | 11,00 |
| C2 | Implementação da base de dados SQL | 12,00 |
| D1 | Casos de uso | 4,00 |
| D2 | Fluxograma ou esquema lógico | 3,00 |
| D3 | Wireframes e interfaces | 5,00 |
| E1 | Implementação das interfaces | 8,00 |
| E2 | Operações CRUD | 10,00 |
| E3 | Manipulação de dados | 8,00 |
| E4 | Validações | 5,00 |
| E5 | Qualidade técnica | 4,00 |
| F1 | Estrutura da interface | 6,00 |
| F2 | Usabilidade | 6,00 |
| F3 | Qualidade da interface | 6,00 |

## Índice dos aspetos CIS

| Aspeto | O que se avalia | Máximo | Requisitos associados |
| --- | --- | ---: | --- |
| A1.M1 | Sequência de desenvolvimento | 1,50 | T-01 |
| A1.M2 | Cumprimento dos requisitos de entrega | 1,50 | M1-07, M2-10, M3-29 |
| A2.M1 | Estrutura de ficheiros e recursos | 1,00 | T-02 |
| A2.M2 | Nomenclatura consistente | 1,00 | M1-07, M2-10, M3-29, T-02 |
| A2.M3 | Cópias de segurança durante o desenvolvimento | 1,00 | T-02 |
| B1.M1 | Interpretação do enunciado | 2,00 | T-01 |
| B1.M2 | Aplicação das especificações técnicas | 2,00 | M3-28 |
| B2.M1 | Execução autónoma e organizada | 1,00 | T-03 |
| B2.M2 | Postura profissional | 1,00 | T-03 |
| C1.M1 | Entidades | 2,00 | M1-01 |
| C1.M2 | Atributos | 1,50 | M1-02 |
| C1.M3 | Chaves primárias | 1,50 | M1-02 |
| C1.M4 | Relações | 2,00 | M1-03 |
| C1.M5 | Chaves estrangeiras | 1,50 | M1-02 |
| C1.M6 | Coerência e ausência de redundância significativa | 1,50 | M1-03 |
| C1.M7 | Organização e legibilidade do modelo | 1,00 | M1-03 |
| C2.M1 | Estrutura da base de dados | 2,00 | M1-04 |
| C2.M2 | Chaves primárias | 1,50 | M1-05 |
| C2.M3 | Chaves estrangeiras | 1,50 | M1-05 |
| C2.M4 | Tipos de dados | 1,50 | M1-05 |
| C2.M5 | Restrições de integridade | 1,00 | M1-05 |
| C2.M6 | Dados de exemplo | 1,50 | M1-06 |
| C2.M7 | Consultas solicitadas | 2,00 | M1-Q1, M1-Q2, M1-Q3, M1-Q4, M1-Q5, M1-Q6 |
| C2.M8 | Execução sem erros críticos | 1,00 | M1-04 |
| D1.M1 | Atores | 1,00 | M2-01 |
| D1.M2 | Casos de uso principais | 2,00 | M2-02 |
| D1.M3 | Ligações entre atores e casos de uso | 1,00 | M2-01 |
| D2.M1 | Fluxo principal | 1,50 | M2-03 |
| D2.M2 | Simbologia ou representação lógica | 1,50 | M2-03 |
| D3.M1 | Ecrãs principais | 2,00 | M2-04, M2-05, M2-06, M2-07, M2-08 |
| D3.M2 | Organização dos elementos | 1,50 | M2-09 |
| D3.M3 | Coerência da navegação | 1,50 | M2-04, M2-09 |
| E1.M1 | Interfaces principais | 2,00 | M3-01, M3-03, M3-04, M3-05, M3-06, M3-07 |
| E1.M2 | Campos dos formulários | 2,00 | M3-03, M3-04, M3-05, M3-06, M3-07 |
| E1.M3 | Navegação entre áreas | 2,00 | M3-01 |
| E1.M4 | Organização dos elementos gráficos | 2,00 | M3-26 |
| E2.M1 | Criação | 2,00 | M3-08 |
| E2.M2 | Consulta e listagem | 2,00 | M3-09 |
| E2.M3 | Edição | 2,00 | M3-10, M3-13 |
| E2.M4 | Eliminação | 2,00 | M3-11 |
| E2.M5 | Funcionamento e consistência do CRUD | 2,00 | M3-12, M3-25 |
| E3.M1 | Comunicação com a base de dados | 2,00 | M3-02, M3-09 |
| E3.M2 | Armazenamento | 2,00 | M3-08, M3-12 |
| E3.M3 | Atualização e eliminação | 2,00 | M3-10, M3-11, M3-12 |
| E3.M4 | Pesquisa e filtragem | 2,00 | M3-14, M3-15, M3-16, M3-17, M3-18 |
| E4.M1 | Campos obrigatórios | 1,50 | M3-19 |
| E4.M2 | Regras de negócio | 2,00 | M3-13, M3-20, M3-21, M3-22, M3-23, M3-24, M3-25 |
| E4.M3 | Mensagens de erro e confirmação | 1,50 | M3-19, M3-27 |
| E5.M1 | Código organizado e estruturado | 2,00 | M3-28 |
| E5.M2 | Execução sem erros críticos | 2,00 | M3-02, M3-29 |
| F1.M1 | Organização dos ecrãs | 2,00 | M3-26 |
| F1.M2 | Distribuição dos componentes | 2,00 | M3-26 |
| F1.M3 | Consistência visual | 2,00 | M3-26 |
| F2.M1 | Navegação simples e intuitiva | 2,00 | M3-01, M3-26 |
| F2.M2 | Legibilidade e facilidade de utilização dos formulários | 2,00 | M3-26 |
| F2.M3 | Identificação de botões e controlos | 2,00 | M3-26 |
| F3.M1 | Feedback ao utilizador | 2,00 | M3-27 |
| F3.M2 | Informação clara, organizada e consistente | 2,00 | M3-05, M3-07, M3-12, M3-26 |
| F3.M3 | Princípios básicos de UI/UX | 2,00 | M3-26 |
