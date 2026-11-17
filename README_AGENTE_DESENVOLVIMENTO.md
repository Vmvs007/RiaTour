# Como criar um agente de desenvolvimento para a WorldSkills

> **Guia de estudo e trabalho — Projeto RiaTour (PHP + MySQL + Bootstrap + JavaScript)**  
> Objetivo: reproduzir um fluxo de desenvolvimento assistido por IA, desde a leitura do enunciado até aos testes e à entrega, **compreendendo e validando todo o código**.

## 1. O que vamos montar

Usaremos **duas ferramentas com papéis diferentes**:

| Ferramenta | Papel | O que pedir |
|---|---|---|
| **Projeto no ChatGPT** | Orientador técnico e pedagógico | Analisar o enunciado, dividir o trabalho, explicar conceitos, propor testes, rever decisões. |
| **Codex no editor ou terminal** | Agente que trabalha no código | Inspecionar ficheiros, criar/alterar código, executar testes, corrigir erros e documentar. |
| **Git + GitHub** | Histórico e partilha | Guardar alterações, comparar versões, rever commits e recuperar trabalho. |

O ChatGPT ajuda a pensar **o que construir e porquê**. O Codex ajuda a **executar as alterações no projeto**. O concorrente deve ser capaz de explicar, testar e adaptar o resultado.

**Não é necessário programar um agente próprio, criar uma API ou treinar um modelo.** A combinação de instruções, ficheiros de referência e um ambiente de execução de código é suficiente.

## 2. Pré-requisitos

- Conta com acesso a **ChatGPT** e **Codex**, conforme a disponibilidade do respetivo plano.
- **VS Code** (ou outro editor compatível com Codex).
- **Git** e acesso ao **GitHub**.
- **PHP** e **MySQL** locais (por exemplo, XAMPP ou Laragon).
- Navegador web para testar a aplicação.
- Este repositório clonado no computador.

Verificar as ferramentas no terminal:

```bash
php -v
git --version
```

No Windows, o comando `php` pode exigir a configuração do `PATH` ou o uso do executável na pasta do XAMPP/Laragon. A base de dados deve estar iniciada antes de testar a aplicação.

**Documentação oficial:** [Codex](https://developers.openai.com/learn/codex), [Codex com o plano ChatGPT](https://help.openai.com/pt-pt/articles/11369540-using-codex-with-your-chatgpt-plan), [Projetos no ChatGPT](https://help.openai.com/pt-pt/articles/10169521-projects-in-chatgpt).

## 3. Criar o projeto de orientação no ChatGPT

1. No ChatGPT, cria um **Projeto** com o nome `WorldSkills — Desenvolvimento de Software`.
2. Adiciona as **instruções do projeto** (texto pronto a copiar abaixo).
3. Junta como materiais de referência, **apenas quando a partilha for autorizada**, o enunciado, a grelha de avaliação e a documentação do projeto.
4. Mantém as conversas deste trabalho no mesmo Projeto para preservar o contexto disponível.
5. Abre uma conversa de **planeamento** e outra de **revisão**, se a divisão ajudar. O código continua a ser editado no repositório pelo Codex.

### Instruções do Projeto ChatGPT (copiar e colar)

```text
És o meu orientador técnico e pedagógico para exercícios de Desenvolvimento de Software da WorldSkills.

PERFIL
Tenho bases de Java, PHP, SQL, JavaScript, HTML e CSS. Quero aprender a desenvolver pequenas aplicações empresariais de forma rápida, correta e autónoma.

OBJETIVOS
- Ler o enunciado e identificar todos os requisitos e entregáveis.
- Relacionar requisitos com os critérios de avaliação.
- Propor um plano curto, com prioridades e tempos.
- Explicar o raciocínio antes de propor soluções extensas.
- Fornecer exemplos pequenos e reutilizáveis, não arquiteturas complexas.
- Rever SQL, PHP, HTML, JavaScript, validações e segurança.
- Ensinar-me a testar, corrigir, documentar e adaptar o projeto.

STACK PREFERENCIAL
PHP nativo, PDO, MySQL, HTML, CSS, Bootstrap e JavaScript Vanilla.
Não utilizar frameworks ou bibliotecas adicionais sem confirmar que são permitidos no exercício.

ESTILO DE ENSINO
- Responde em português de Portugal.
- Usa nomes de funções e variáveis em inglês.
- Explica os conceitos com exemplos concretos.
- Identifica o que posso reutilizar e o que muda quando o negócio muda.
- Faz perguntas de verificação ou pequenos desafios, em vez de resolver sempre tudo por mim.
- Não inventes requisitos nem resultados de testes.
- Quando houver conflito entre documentos, identifica-o expressamente.

FLUXO DE TRABALHO
1. Interpretar o enunciado.
2. Criar checklist e plano.
3. Modelar entidades e relações.
4. Implementar a base de dados.
5. Criar a interface e os CRUD.
6. Implementar regras de negócio e validações.
7. Testar casos válidos e inválidos.
8. Preparar documentação e entregas.
9. Rever pela grelha de avaliação.

Age como tutor e revisor técnico; não assumas que código gerado é automaticamente correto.
```

> **Importante:** as instruções do Projeto ChatGPT ajudam a orientar as respostas, mas não substituem o acesso do Codex aos ficheiros reais. Também não sincronizam automaticamente uma pasta local com as conversas.

## 4. Preparar o repositório para o Codex

Abre a pasta do repositório no VS Code e inicia o Codex pela extensão, aplicação ou CLI, seguindo as instruções oficiais de instalação e autenticação. **Seleciona a pasta do projeto como área de trabalho**; é aí que o agente deverá ler e alterar os ficheiros.

O principal ficheiro de orientação do Codex é o **`AGENTS.md` na raiz**. O Codex pode utilizá-lo como contexto persistente do repositório. Se já existir, **lê-o e adapta-o** em vez de o sobrescrever.

### Exemplo mínimo de `AGENTS.md` (para copiar/adaptar)

```markdown
# Instruções para o agente de desenvolvimento — RiaTour

## Objetivo
Projeto pedagógico de gestão de barcos, percursos, passeios, clientes e reservas.
Prioridade: código correto, simples, legível e fácil de adaptar.

## Stack
PHP nativo, PDO, MySQL, HTML, Bootstrap local e JavaScript Vanilla.
Sem Laravel, ORM, React ou outras frameworks sem autorização.

## Convenções
- Documentação e interface em português de Portugal.
- Funções, variáveis e comentários técnicos em inglês.
- Comentários úteis, não linha a linha.
- Usa etiquetas: `EXEMPLO`, `ADAPTAR`, `ATENCAO`.
- Mantém separadas configuração, funções reutilizáveis, páginas e SQL.
- Não introduzas dependências nem refatorações grandes sem necessidade.

## Segurança e qualidade
- SQL parametrizado com PDO.
- Escape de valores apresentados em HTML.
- Validação de dados no servidor.
- Proteção CSRF em operações que alterem dados.
- Nunca guardar segredos no repositório.
- Respeitar integridade referencial e regras de capacidade.

## Como trabalhar
1. Inspeciona os ficheiros relevantes antes de alterar.
2. Resume a alteração planeada em poucas linhas.
3. Modifica apenas os ficheiros necessários.
4. Executa testes ou explica exatamente o que não conseguiste testar.
5. Resume ficheiros alterados, decisões e testes.
6. Indica o que falta e como reproduzir a verificação.

## Regras de negócio RiaTour
- Um passeio associa um barco e um percurso a uma data/hora.
- Uma reserva associa cliente, passeio e número de passageiros.
- Não aceitar quantidade de passageiros <= 0.
- Não ultrapassar a capacidade do barco.
- Reservas canceladas não ocupam lugares.
- Ao editar reservas, recalcular a ocupação sem contar duas vezes a reserva editada.
```

O `AGENTS.md` não executa código nem instala ferramentas: contém **instruções para o agente**. O ficheiro `README.md` explica o projeto a humanos. Este documento explica **como trabalhar com o agente**.

## 5. Workflow recomendado: um módulo de cada vez

Não pedir "faz a prova toda" num único prompt. Trabalhar em incrementos verificáveis:

| Etapa | ChatGPT (tutor) | Codex (executor) | Verificação pessoal |
|---|---|---|---|
| **1. Análise** | Extrair requisitos, tempos, critérios | Ler árvore de ficheiros e propor plano | Confirmar cada requisito no enunciado. |
| **2. Dados** | Rever entidades e normalização | Criar SQL e dados de exemplo | Importar DB e executar consultas. |
| **3. Desenho** | Rever casos de uso, fluxo e ecrãs | Preparar diagramas/documentos editáveis | Conferir atores, decisões e navegação. |
| **4. CRUD** | Explicar padrão de operações | Implementar um CRUD de cada vez | Criar, listar, editar, pesquisar e eliminar. |
| **5. Negócio** | Esclarecer regras de reservas | Implementar capacidade e estados | Tentar exceder lotação e editar reservas. |
| **6. Entrega** | Rever critérios CIS | Executar testes e preparar ficheiros | Abrir os ZIP/PDF e conferir nomes. |

### Prompt A — Começar uma sessão de trabalho

```text
Lê o AGENTS.md, o README.md e os ficheiros relevantes do repositório.
Não alteres nada ainda.
Resume: (1) estado atual, (2) próximos requisitos por cumprir,
(3) ficheiros que provavelmente serão afetados, (4) testes disponíveis.
Propõe a próxima tarefa pequena, com critérios de aceitação verificáveis.
```

### Prompt B — Implementar uma funcionalidade (exemplo: barcos)

```text
Implementa apenas o CRUD de barcos, seguindo AGENTS.md.
Campos: id, name, capacity e status.
Usa PDO preparado, Bootstrap e validação no servidor.
Inclui criar, listar, editar, eliminar e pesquisar por nome/estado.
Trata eliminações impedidas por relações existentes sem quebrar a aplicação.
Adiciona comentários EXEMPLO, ADAPTAR e ATENCAO apenas onde ajudam.
Não alteres os outros módulos nem introduzas frameworks.
No fim, executa as verificações possíveis e indica como testar manualmente.
```

### Prompt C — Explicar código em vez de o copiar cegamente

```text
Explica a função [NOME_DA_FUNCAO] existente no ficheiro [CAMINHO].
Diz-me o que recebe, o que devolve, que SQL executa e que erros evita.
Apresenta um exemplo curto de utilização.
Depois propõe uma pequena alteração para eu fazer autonomamente,
sem apresentares logo a solução.
```

### Prompt D — Rever a regra de capacidade de reservas

```text
Analisa o código de criação e edição de reservas.
Verifica:
- passageiros > 0;
- passeio existente, disponível e em data permitida;
- total de passageiros de reservas pendentes/confirmadas <= lotação;
- reservas canceladas excluídas do total;
- edição sem contar duas vezes os lugares originais;
- tratamento de operações simultâneas, quando aplicável.

Primeiro indica problemas e casos de teste.
Depois corrige apenas os problemas confirmados, sem reescrever tudo.
Mostra evidência dos testes executados e dos que não foram executados.
```

### Prompt E — Revisão final pela grelha

```text
Compara o projeto com o enunciado e a grelha CIS disponíveis.
Cria uma matriz: requisito, critério, ficheiro, teste, estado.
Classifica cada requisito como cumprido, parcial, por testar ou em falta.
Não atribuas pontos máximos automaticamente.
Lista primeiro os problemas críticos e as melhorias com maior impacto.
Não alteres código sem apresentar o plano.
```

## 6. Como adaptar a outro negócio

Antes de escrever código novo, traduz as entidades e as regras:

| RiaTour | Exemplo: biblioteca | Exemplo: reservas de salas |
|---|---|---|
| Barco (`boats`) | Exemplar ou recurso | Sala (`rooms`) |
| Percurso (`routes`) | Tipo de empréstimo | Tipo de utilização |
| Passeio (`trips`) | Disponibilidade por data | Sessão/horário |
| Cliente (`customers`) | Leitor | Utilizador |
| Reserva (`bookings`) | Empréstimo | Reserva de sala |
| Lotação | Exemplares disponíveis | Capacidade da sala |

**Prompt de adaptação:**

```text
Quero adaptar o RiaTour ao seguinte domínio: [DESCREVER NEGÓCIO].
Antes de programar, cria uma tabela com entidades, atributos, relações,
regras de negócio e correspondência com os módulos atuais.
Indica quais os ficheiros que podem ficar iguais, quais precisam de
pequenas alterações e quais têm lógica específica que deve ser refeita.
Produz um plano pequeno e testável. Não implementes já as mudanças.
```

**Atenção:** uma semelhança entre entidades não garante que a regra de negócio seja idêntica. Reavaliar sempre datas, disponibilidade, estados, restrições e operações de eliminação.

## 7. Regras práticas para obter melhores resultados

1. **Dar contexto concreto:** objetivo, ficheiros relevantes, requisitos e como testar.
2. **Limitar o âmbito:** uma funcionalidade ou módulo por pedido.
3. **Pedir código executável:** não aceitar pseudocódigo quando se espera uma alteração real.
4. **Pedir testes:** incluir pelo menos um caso normal, um inválido e um limite.
5. **Rever o diff:** perceber exatamente o que mudou antes de aceitar.
6. **Guardar versões:** criar commits pequenos e com mensagens claras.
7. **Explicar o código:** conseguir responder por que existe cada tabela, `JOIN`, `WHERE` ou validação.
8. **Tratar a IA como assistente:** verificar sempre os resultados, em vez de os assumir corretos.

### Pequena checklist após cada tarefa

- [ ] Consigo explicar a alteração sem ler a resposta do Codex?
- [ ] A funcionalidade corre no meu computador?
- [ ] Testei pelo menos um erro e um caso-limite?
- [ ] Os dados ficam corretos no MySQL?
- [ ] Não quebrei outras páginas?
- [ ] Os comentários identificam as partes adaptáveis?
- [ ] Não existem passwords, chaves ou dados privados no commit?
- [ ] Fiz commit após confirmar que está tudo correto?

## 8. Git e GitHub sem perder trabalho

Exemplo, na pasta do repositório já clonado:

```bash
git status
git diff
git add .
git commit -m "feat: add boats CRUD"
git push
```

**Antes de `git add .`:** confirmar que o `.gitignore` exclui `.env`, ficheiros de configuração com credenciais, caches, logs, bases de dados privadas, documentos confidenciais e ficheiros de prova cuja publicação não esteja autorizada.

Se possível, criar uma branch para funcionalidades maiores e rever as alterações antes de as integrar. Não usar comandos destrutivos (`git reset --hard`, `git push --force`) sem perceber o impacto.

## 9. Simulacro de prova e limites de utilização

Durante a **preparação**, usar o agente como tutor, explicador e revisor. O objetivo é aprender a executar as tarefas sem depender dele.

Durante a **competição**, utilizar ChatGPT, Codex, acesso ao GitHub, projetos-base ou documentação externa **apenas se as regras oficiais e o júri o permitirem expressamente**. O acesso a Internet ou a existência de ferramentas instaladas não implica autorização de uso. Respeitar confidencialidade de enunciados, igualdade entre concorrentes e limites de comunicação com o jurado.

Para treinar autonomia, fazer pelo menos um simulacro **sem IA**, com o mesmo tempo e as mesmas restrições do enunciado correspondente. O projeto preparado é um **material de estudo**, não uma autorização para transportar código pré-feito para a prova.

## 10. Referências úteis

- [OpenAI — Codex](https://developers.openai.com/learn/codex)
- [OpenAI — Utilizar o Codex com o plano ChatGPT](https://help.openai.com/pt-pt/articles/11369540-using-codex-with-your-chatgpt-plan)
- [OpenAI — Projetos no ChatGPT](https://help.openai.com/pt-pt/articles/10169521-projects-in-chatgpt)
- [OpenAI — Workflows iterativos e AGENTS.md](https://developers.openai.com/cookbook/examples/codex/iterating-development-workflows-with-codex)

---

**Resultado esperado:** um concorrente capaz de transformar um enunciado num plano, modelar a base de dados, desenvolver CRUD, validar regras de negócio, testar e entregar um projeto — usando a IA para aprender e rever, sem perder autonomia técnica.
