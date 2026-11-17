# Requisitos da prova RiaTour

WorldSkills Portugal Centro 2027 · Fase 1 Regional · Profissão 09 — Desenvolvimento de Software

A RiaTour organiza passeios em barcos moliceiros em Aveiro. A solução centraliza a gestão de barcos, percursos, passeios, clientes e reservas. A [matriz CIS](03-MATRIZ-CIS.md) associa cada requisito a evidências e testes. O [plano de seis horas](02-PLANEAMENTO.md) define a sequência e os mínimos de cada etapa.

## Fontes

| Documento | Utilização |
| --- | --- |
| [Enunciado RiaTour](../Enunciados/SP27_09_Desenvolvimento_de_Software_PR1_Prova_Regional1.pdf) | Fonte dos requisitos da prova. Secções 4 a 6, pp. 5–14 da numeração impressa. |
| [Marking Form CIS](../Enunciados/CIS_PR_Centro_2027_marking_form_Desenvolvimento_de_Software.pdf) | Fonte dos aspetos e dos máximos de avaliação. |
| [Guia de Prova](../Enunciados/SP27_09_Desenvolvimento_de_Software_PR1_Guia%20de%20prova_Regional1.pdf) | Horário, organização do posto e orientações para a realização da prova. |
| [Lista de Infraestruturas](../Enunciados/SP27_09_Desenvolvimento_de_Software_PR1_LI_Regional1.pdf) | Ambiente Windows, ferramentas previstas e condições do posto, incluindo a proibição de suportes de armazenamento externos trazidos pela concorrente. |
| [AGENTS.md](../AGENTS.md) | Regras técnicas e pedagógicas do projeto de referência. |

## Módulo 1 — Base de dados relacional e SQL

### Modelo e implementação · M1-01 a M1-05

| Entidade | Dados a representar | Relações e estados |
| --- | --- | --- |
| Barco | Identificador, nome, capacidade máxima de passageiros, estado de disponibilidade | Um barco pode estar associado a vários passeios. O enunciado usa «ativos» na primeira consulta e descreve a disponibilidade do barco. |
| Percurso | Identificador, nome, descrição, duração estimada, preço por passageiro | Um percurso pode ser usado em vários passeios. |
| Passeio (horário) | Identificador, barco, percurso, data, hora e estado | Cada passeio tem um barco e um percurso. Estados indicados: disponível, esgotado e cancelado. |
| Cliente | Identificador, nome, email e contacto telefónico | Um cliente pode ter várias reservas. |
| Reserva | Identificador, cliente, passeio, número de passageiros, data da reserva e estado | Cada reserva liga um cliente a um passeio. Estados indicados: pendente, confirmada e cancelada. A soma de passageiros do passeio deve respeitar a capacidade do barco. |

Apresentar entidades, atributos, chaves e cardinalidades num modelo coerente, normalizado e legível. Criar a base `bdRiaTour_NomeConcorrente_C27` com chaves primárias em todas as tabelas, chaves estrangeiras onde aplicável, tipos adequados e restrições de integridade sempre que possível. A prova admite MySQL ou Microsoft SQL Server. O projeto de referência usa MySQL. O dicionário de dados é opcional.

### Dados e consultas · M1-06 e M1-Q1 a M1-Q6

Preencher todas as tabelas com pelo menos dois barcos, dois percursos, dois clientes, três horários e três reservas. Para verificar as consultas, usar exemplos que distingam barcos ativos de indisponíveis e horários disponíveis de outros estados. Incluir reservas que permitam conferir à mão um total por horário.

1. Listar todos os barcos ativos.
2. Listar todos os percursos existentes, ordenados pelo nome.
3. Listar todos os horários disponíveis com o nome do barco e o nome do percurso.
4. Listar todas as reservas com o nome do cliente, o percurso, a data e a hora do passeio.
5. Calcular o número total de passageiros reservados por horário.
6. Apresentar os horários de um determinado barco.

### Entrega · M1-07

`WSC27_NomeConcorrente_M1.zip` com `DB_RiaTour_NomeConcorrente_M1.sql` (estrutura e dados) e `ER_RiaTour_NomeConcorrente_M1.pdf` (modelo, PDF escolhido entre os formatos admitidos). Guardar as consultas na exportação ou entregar `SQL_RiaTour_NomeConcorrente_M1.sql` no mesmo ZIP.

## Módulo 2 — Análise e desenho da aplicação

### Casos de uso e fluxograma · M2-01 a M2-03

- **Operador** — gerir barcos moliceiros, percursos turísticos, horários de passeio, clientes e reservas. Representar também consulta de reservas existentes e alteração do estado da reserva.
- **Cliente** — consultar passeios disponíveis, efetuar uma reserva e cancelar uma reserva.
- **Criação de reserva** — selecionar o horário/passeio, inserir ou selecionar o cliente, indicar o número de passageiros e verificar disponibilidade. Com disponibilidade, registar e apresentar sucesso. Em caso de erro, informar o utilizador. O enunciado aceita fluxograma, algoritmo ou esquema lógico simples.

### Wireframes · M2-04 a M2-09

| Ecrã mínimo | Conteúdo e ações |
| --- | --- |
| Inicial | Menu e acesso a Barcos, Percursos, Horários, Clientes e Reservas. |
| Barcos | Lista com nome, capacidade máxima e estado. Criar, editar e eliminar. |
| Percursos | Lista com nome, duração estimada e preço base. Criar, editar e eliminar. |
| Horários | Lista com barco, percurso, data, hora e estado. Criar, editar e eliminar. |
| Reservas | Lista com cliente, percurso, barco, data e hora do passeio, passageiros e estado. Criar, editar e cancelar ou eliminar. |

Usar desenhos de baixa fidelidade, legíveis e coerentes com os casos de uso, com o fluxograma e entre si. Organizar tabelas, controlos e campos dos formulários de forma a permitir seguir a navegação e perceber cada operação.

### Entrega · M2-10

`WSC27_NomeConcorrente_M2.zip` com `UC_RiaTour_NomeConcorrente_M2.pdf`, `FLUXO_RiaTour_NomeConcorrente_M2.pdf` e `UI_RiaTour_NomeConcorrente_M2.pdf`. O diagrama pode ser entregue em formato à escolha segundo o texto da prova. Assume-se PDF para coincidir com os três nomes exemplificados.

## Módulo 3 — Gestão de registos e manipulação de dados

- **Entrada e dados** (M3-01 a M3-07). Menu para as cinco áreas. Ligação à base do M1 e apresentação dos atributos da ficha de dados, incluindo identificador. Exibir nomes ou descrições para as relações sempre que possível, por exemplo o nome do barco num passeio.
- **Operações** (M3-08 a M3-13). Em cada área, criar, listar, visualizar um registo, editar e eliminar. Confirmar a persistência e preservar relações na base de dados. Nas reservas, permitir também a alteração de estado.
- **Pesquisas** (M3-14 a M3-18). Barcos por nome ou estado. Percursos por nome. Passeios por data, barco ou percurso. Clientes por nome ou email. Reservas por cliente, passeio ou estado.
- **Validações** (M3-19 a M3-25). Rejeitar campos obrigatórios vazios, capacidade de barco, duração, preço por passageiro ou quantidade de passageiros iguais a zero ou negativos, datas de passeio anteriores ao dia atual e reservas que excedam a capacidade total do barco para o passeio.
- **UI/UX** (M3-26 a M3-27). Manter navegação simples, ecrãs e tabelas organizados, ações identificadas, formulários legíveis, componentes adequados e mensagens de sucesso, erro e confirmação.

### Regras do projeto · M3-28

PHP nativo com PDO, MySQL, HTML5, CSS3, Bootstrap 5 local e JavaScript Vanilla. Sem frameworks adicionais, ORM, APIs externas ou autenticação. Usar consultas preparadas, escape de HTML, validação no servidor e proteção CSRF nas operações de alteração. Manter uma organização simples e modular. Usar nomes de funções e variáveis em inglês, com comentários em inglês marcados por `EXEMPLO`, `ADAPTAR` ou `ATENCAO` conforme o objetivo. Escrever a interface e a documentação em português de Portugal.

### Entrega · M3-29

`WSC27_NomeConcorrente_M3.zip` com código-fonte, exportação da base se tiver sido alterada durante o desenvolvimento do M3 e instruções em `README_RiaTour_NomeConcorrente_M3.pdf` ou `README_RiaTour_NomeConcorrente_M3.txt`. O TXT é a opção prevista no plano. A primeira importação pode usar a exportação do M1 quando a base se mantiver igual.

## Ambiguidades a esclarecer

| Ponto | Leitura de trabalho para o projeto |
| --- | --- |
| Número de módulos e horário | O enunciado e a abertura do Guia referem quatro módulos. O enunciado descreve três e o quadro do Guia totaliza 90 + 45 + 225 minutos nesses três. Seguir as durações pedidas. O briefing é indicado como 30 minutos a partir das 08:30, mas o quadro inicia o M1 às 08:40. Confirmar o horário operacional com o júri em contexto de prova. |
| «Horário» e «passeio» | Usar uma entidade para uma ocorrência com barco, percurso, data e hora. Representar «horários» no M2 e «passeios» no M3. A referência à base do «módulo anterior» no M3 é lida à luz da indicação expressa de reutilizar a base do M1. |
| Cliente no desenho e na aplicação | O Cliente é ator obrigatório no M2. O M3 especifica interfaces de gestão e dispensa autenticação. Representar os casos do Cliente no diagrama e planear o M3 para o operador, sem assumir portal de cliente. |
| Wireframe de clientes | O menu do M2 deve ligar à gestão de clientes. O ecrã próprio de clientes fica fora dos cinco wireframes enumerados. A gestão de clientes é explícita no M3. |
| «Preço base» no M2 e «preço por passageiro» nos M1 e M3 | Tratar como o mesmo valor unitário para o projeto, com rótulo explícito no formulário. |
| Estados e disponibilidade | «Ativo» e «disponível» surgem associados ao barco. O enunciado enumera estados para passeio e reserva, mas não fixa todos os rótulos dos barcos nem as transições ou automatismos de estado. Usar um domínio simples e documentar a relação entre disponibilidade, estado e capacidade. |
| Lotação e consulta de totais | O texto não indica quais os estados de reserva que ocupam lugares. Proposta a validar: contar pendentes e confirmadas para a lotação e libertar lugares ao cancelar. No relatório Q5, explicitar os estados contabilizados e se os horários sem reservas aparecem a zero. A consulta Q4 pede todas as reservas. |
| Duplicação e sobreposição de horários | O M1 menciona evitar duplicações na contextualização, sem definir a chave nem impor controlo de sobreposições. Esclarecer se o mesmo barco com a mesma data e hora deve ser bloqueado e se a duração entra na regra antes de fixar uma restrição específica. |
| Eliminar e cancelar | O wireframe de reservas admite cancelar ou eliminar. No M3 há eliminação e alteração de estado. Prever ambas. A política de eliminação de registos referenciados fica em aberto. O plano admite bloquear com mensagem para preservar a integridade. |
| Datas, campos e filtros | A regra explícita compara datas, sem proibir horas anteriores no próprio dia. Os campos apresentados e a regra de obrigatoriedade são dados, mas o texto não define todos os campos editáveis, quando preencher a data da reserva, pesquisa parcial ou exata, nem como combinar filtros. Fixar e documentar escolhas simples antes de testar. |
| Nomes de entrega e identificação | O enunciado usa `NomeConcorrente` nos exemplos. O Guia refere identificação por número e anonimato. Confirmar como preencher o marcador no posto e conservar os restantes nomes. |
| Formatos dos diagramas | O M1 aceita vários formatos e o M2 deixa escolher o formato do diagrama de casos de uso, embora os exemplos terminem em `.pdf`. Usar PDF no plano para coincidir com os exemplos. |
