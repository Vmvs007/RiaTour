# Autoavaliação CIS — revisão final

Revisão de 17/11/2026. Comparação com o enunciado RiaTour e os 59 aspetos do Marking Form CIS, transcritos em 03-MATRIZ-CIS.md. Os máximos totalizam 100 pontos. O estado indica a evidência disponível e não atribui uma nota.

## Testes executados

- T1 — Importação do esquema e dados numa base temporária MariaDB 10.4.32. Execução integral de 03_queries.sql sem erros.
- T2 — CRUD por HTTP nas cinco áreas, persistência, IDs inválidos, CSRF e eliminações com dependências.
- T3 — Onze casos de pesquisa, cobrindo todos os campos pedidos, nomes por JOIN e navegação no HTML.
- T4 — Campos inválidos, valores preservados, erros junto dos campos, mensagens de sucesso e datas passadas.
- T5 — Reservas de 4 e depois 6 lugares num barco de 10, rejeição de mais 1, cancelamento, edição de 4 para 3 passageiros, rejeição de passeio cancelado e dependências.
- T6 — Dois processos PHP pedem 7 lugares no mesmo passeio de 10. Um aceite, outro rejeitado, ocupação final 7.
- T7 — Sintaxe PHP e integridade dos três ZIP. Modelo ER exportado e revisto visualmente. Os PDFs do M2 foram produzidos e revistos na fase de desenho.

## Verificações manuais pendentes

- M1 — Importar e executar no MySQL do posto. O ensaio atual usa MariaDB.
- M2 — Navegar num browser em ecrã grande e estreito, testar teclado, menu móvel e legibilidade. Os testes atuais verificam respostas HTTP.
- M3 — Observar a concorrente durante a prova para autonomia, postura e execução do plano. Confirmar backups realizados durante o trabalho.
- M4 — Conferir cada resultado SQL na ferramenta do posto e testar uma instalação a partir dos ZIP extraídos.

## Avaliação por aspeto

| ID | Descrição | Máximo | Estado | Evidência | Teste |
| --- | --- | ---: | --- | --- | --- |
| A1.M1 | Sequência de desenvolvimento | 1,50 | parcial | docs/02-PLANEAMENTO.md, AGENTS.md e estrutura do projeto | T7, M3 |
| A1.M2 | Cumprimento dos requisitos de entrega | 1,50 | parcial | docs/02-PLANEAMENTO.md, AGENTS.md e estrutura do projeto | T7, M3 |
| A2.M1 | Estrutura de ficheiros e recursos | 1,00 | parcial | docs/02-PLANEAMENTO.md, AGENTS.md e estrutura do projeto | T7, M3 |
| A2.M2 | Nomenclatura consistente | 1,00 | parcial | docs/02-PLANEAMENTO.md, AGENTS.md e estrutura do projeto | T7, M3 |
| A2.M3 | Cópias de segurança durante o desenvolvimento | 1,00 | não cumprido | Sem evidência verificável de backups realizados durante o desenvolvimento | T7, M3 |
| B1.M1 | Interpretação do enunciado | 2,00 | parcial | docs/02-PLANEAMENTO.md, AGENTS.md e estrutura do projeto | T7, M3 |
| B1.M2 | Aplicação das especificações técnicas | 2,00 | parcial | docs/02-PLANEAMENTO.md, AGENTS.md e estrutura do projeto | T7, M3 |
| B2.M1 | Execução autónoma e organizada | 1,00 | parcial | docs/02-PLANEAMENTO.md, AGENTS.md e estrutura do projeto | T7, M3 |
| B2.M2 | Postura profissional | 1,00 | parcial | docs/02-PLANEAMENTO.md, AGENTS.md e estrutura do projeto | T7, M3 |
| C1.M1 | Entidades | 2,00 | cumprido | database/01_schema.sql, docs/04-MODELO-ER.md e PDF ER em entregas/M1 | T1, T7 |
| C1.M2 | Atributos | 1,50 | cumprido | database/01_schema.sql, docs/04-MODELO-ER.md e PDF ER em entregas/M1 | T1, T7 |
| C1.M3 | Chaves primárias | 1,50 | cumprido | database/01_schema.sql, docs/04-MODELO-ER.md e PDF ER em entregas/M1 | T1, T7 |
| C1.M4 | Relações | 2,00 | cumprido | database/01_schema.sql, docs/04-MODELO-ER.md e PDF ER em entregas/M1 | T1, T7 |
| C1.M5 | Chaves estrangeiras | 1,50 | cumprido | database/01_schema.sql, docs/04-MODELO-ER.md e PDF ER em entregas/M1 | T1, T7 |
| C1.M6 | Coerência e ausência de redundância significativa | 1,50 | cumprido | database/01_schema.sql, docs/04-MODELO-ER.md e PDF ER em entregas/M1 | T1, T7 |
| C1.M7 | Organização e legibilidade do modelo | 1,00 | cumprido | database/01_schema.sql, docs/04-MODELO-ER.md e PDF ER em entregas/M1 | T1, T7 |
| C2.M1 | Estrutura da base de dados | 2,00 | parcial | database/01_schema.sql, 02_seed.sql e 03_queries.sql | T1, M1, M4 |
| C2.M2 | Chaves primárias | 1,50 | parcial | database/01_schema.sql, 02_seed.sql e 03_queries.sql | T1, M1, M4 |
| C2.M3 | Chaves estrangeiras | 1,50 | parcial | database/01_schema.sql, 02_seed.sql e 03_queries.sql | T1, M1, M4 |
| C2.M4 | Tipos de dados | 1,50 | parcial | database/01_schema.sql, 02_seed.sql e 03_queries.sql | T1, M1, M4 |
| C2.M5 | Restrições de integridade | 1,00 | parcial | database/01_schema.sql, 02_seed.sql e 03_queries.sql | T1, M1, M4 |
| C2.M6 | Dados de exemplo | 1,50 | parcial | database/01_schema.sql, 02_seed.sql e 03_queries.sql | T1, M1, M4 |
| C2.M7 | Consultas solicitadas | 2,00 | parcial | database/01_schema.sql, 02_seed.sql e 03_queries.sql | T1, M1, M4 |
| C2.M8 | Execução sem erros críticos | 1,00 | parcial | database/01_schema.sql, 02_seed.sql e 03_queries.sql | T1, M1, M4 |
| D1.M1 | Atores | 1,00 | cumprido | design/*.svg e entregas/M2/*.pdf | T7 |
| D1.M2 | Casos de uso principais | 2,00 | cumprido | design/*.svg e entregas/M2/*.pdf | T7 |
| D1.M3 | Ligações entre atores e casos de uso | 1,00 | cumprido | design/*.svg e entregas/M2/*.pdf | T7 |
| D2.M1 | Fluxo principal | 1,50 | cumprido | design/*.svg e entregas/M2/*.pdf | T7 |
| D2.M2 | Simbologia ou representação lógica | 1,50 | cumprido | design/*.svg e entregas/M2/*.pdf | T7 |
| D3.M1 | Ecrãs principais | 2,00 | cumprido | design/*.svg e entregas/M2/*.pdf | T7 |
| D3.M2 | Organização dos elementos | 1,50 | cumprido | design/*.svg e entregas/M2/*.pdf | T7 |
| D3.M3 | Coerência da navegação | 1,50 | cumprido | design/*.svg e entregas/M2/*.pdf | T7 |
| E1.M1 | Interfaces principais | 2,00 | cumprido | Código em app/ e public/ | T2–T6 |
| E1.M2 | Campos dos formulários | 2,00 | cumprido | Código em app/ e public/ | T2–T6 |
| E1.M3 | Navegação entre áreas | 2,00 | cumprido | Código em app/ e public/ | T2–T6 |
| E1.M4 | Organização dos elementos gráficos | 2,00 | parcial | app/views/, public/assets/riatour.css e formulários | T3–T4, M2 |
| E2.M1 | Criação | 2,00 | cumprido | Código em app/ e public/ | T2–T6 |
| E2.M2 | Consulta e listagem | 2,00 | cumprido | Código em app/ e public/ | T2–T6 |
| E2.M3 | Edição | 2,00 | cumprido | Código em app/ e public/ | T2–T6 |
| E2.M4 | Eliminação | 2,00 | cumprido | Código em app/ e public/ | T2–T6 |
| E2.M5 | Funcionamento e consistência do CRUD | 2,00 | cumprido | Código em app/ e public/ | T2–T6 |
| E3.M1 | Comunicação com a base de dados | 2,00 | cumprido | Código em app/ e public/ | T2–T6 |
| E3.M2 | Armazenamento | 2,00 | cumprido | Código em app/ e public/ | T2–T6 |
| E3.M3 | Atualização e eliminação | 2,00 | cumprido | Código em app/ e public/ | T2–T6 |
| E3.M4 | Pesquisa e filtragem | 2,00 | cumprido | Código em app/ e public/ | T2–T6 |
| E4.M1 | Campos obrigatórios | 1,50 | cumprido | Código em app/ e public/ | T2–T6 |
| E4.M2 | Regras de negócio | 2,00 | cumprido | Código em app/ e public/ | T2–T6 |
| E4.M3 | Mensagens de erro e confirmação | 1,50 | cumprido | Código em app/ e public/ | T2–T6 |
| E5.M1 | Código organizado e estruturado | 2,00 | cumprido | Código em app/ e public/ | T2–T6 |
| E5.M2 | Execução sem erros críticos | 2,00 | parcial | Código em app/ e public/ | T1–T7, M1–M2 |
| F1.M1 | Organização dos ecrãs | 2,00 | parcial | app/views/, public/assets/riatour.css e formulários | T3–T4, M2 |
| F1.M2 | Distribuição dos componentes | 2,00 | parcial | app/views/, public/assets/riatour.css e formulários | T3–T4, M2 |
| F1.M3 | Consistência visual | 2,00 | parcial | app/views/, public/assets/riatour.css e formulários | T3–T4, M2 |
| F2.M1 | Navegação simples e intuitiva | 2,00 | parcial | app/views/, public/assets/riatour.css e formulários | T3–T4, M2 |
| F2.M2 | Legibilidade e facilidade de utilização dos formulários | 2,00 | parcial | app/views/, public/assets/riatour.css e formulários | T3–T4, M2 |
| F2.M3 | Identificação de botões e controlos | 2,00 | parcial | app/views/, public/assets/riatour.css e formulários | T3–T4, M2 |
| F3.M1 | Feedback ao utilizador | 2,00 | parcial | app/views/, public/assets/riatour.css e formulários | T3–T4, M2 |
| F3.M2 | Informação clara, organizada e consistente | 2,00 | parcial | app/views/, public/assets/riatour.css e formulários | T3–T4, M2 |
| F3.M3 | Princípios básicos de UI/UX | 2,00 | parcial | app/views/, public/assets/riatour.css e formulários | T3–T4, M2 |

## Entregas

Os diretórios entregas/M1, M2 e M3 têm os conteúdos dos ZIP correspondentes. NomeConcorrente foi confirmado pelo utilizador. O SQL de entrega reconstitui esquema e dados exemplificativos a partir dos scripts testados. A configuração do M3 contém valores locais de exemplo e palavra-passe vazia. Os ZIP incluem apenas os artefactos requeridos, código e recursos locais.

Não foram observados erros críticos nos percursos executados. A inspeção visual da aplicação, a execução em MySQL e a instalação independente da entrega continuam pendentes.
