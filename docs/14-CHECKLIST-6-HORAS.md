# Checklist de seis horas

Os tempos são líquidos. Usar esta lista para confirmar cada etapa antes da entrega.

## Módulo 1 — 90 minutos

- [ ] Até aos 20 minutos, identificar entidades, atributos, PK, FK e cardinalidades.
- [ ] Até aos 45 minutos, criar a base, tabelas e restrições.
- [ ] Até aos 60 minutos, inserir pelo menos 2 barcos, 2 percursos, 2 clientes, 3 passeios e 3 reservas.
- [ ] Até aos 80 minutos, executar as seis consultas e conferir nomes, filtros, ordenação e totais.
- [ ] Até aos 90 minutos, importar o SQL de entrega numa base nova e abrir o PDF ER.
- [ ] Confirmar DB_RiaTour_NomeConcorrente_M1.sql, ER_RiaTour_NomeConcorrente_M1.pdf e SQL_RiaTour_NomeConcorrente_M1.sql no ZIP.

## Módulo 2 — 45 minutos

- [ ] Até aos 10 minutos, representar os dois atores e todos os casos de uso.
- [ ] Até aos 20 minutos, completar o fluxo de reserva com decisões, sucesso e erro.
- [ ] Até aos 40 minutos, completar dashboard, barcos, percursos, passeios, reservas e a representação de clientes.
- [ ] Até aos 45 minutos, abrir os PDFs e conferir legibilidade, campos, ações e navegação.
- [ ] Confirmar os ficheiros UC, FLUXO e UI com o sufixo _RiaTour_NomeConcorrente_M2.pdf.

## Módulo 3 — 225 minutos

- [ ] Até aos 60 minutos, ligação PDO, menu, barcos e percursos funcionais.
- [ ] Até aos 115 minutos, clientes e passeios com nomes nas relações e validações.
- [ ] Até aos 160 minutos, reservas e alterações de estado com controlo de lotação.
- [ ] Até aos 180 minutos, todos os campos de pesquisa e filtros.
- [ ] Até aos 200 minutos, executar os oito cenários de reserva e eliminação.
- [ ] Até aos 210 minutos, corrigir falhas e conferir a interface no navegador.
- [ ] Até aos 225 minutos, preparar e testar uma instalação extraída do ZIP.

## Confirmar antes de entregar

- [ ] Nome e identificação coincidem com as instruções do júri. Neste projeto foi confirmado NomeConcorrente.
- [ ] As três pastas e os três ZIP abrem sem erros.
- [ ] SQL importa e executa no SGBD do posto.
- [ ] CRUD completo nas cinco áreas, incluindo detalhe e confirmação de eliminação.
- [ ] Pesquisas de barcos por nome/estado, percursos por nome, passeios por data/barco/percurso, clientes por nome/email e reservas por cliente/passeio/estado.
- [ ] Campos obrigatórios, positivos, datas, referências e estados inválidos são rejeitados.
- [ ] Reserva dentro da capacidade e reserva exatamente igual ao restante são aceites.
- [ ] Reserva superior ao restante é rejeitada, inclusive em pedidos simultâneos.
- [ ] Cancelar liberta lugares. Editar exclui a própria reserva do total anterior.
- [ ] Passeio cancelado rejeita reservas que ocupem lugares. Passeio no passado é rejeitado.
- [ ] Eliminação com dependências mantém a integridade e apresenta mensagem compreensível.
- [ ] Sucesso, erro e valores reenviados aparecem corretamente.
- [ ] Interface verificada em largura pequena, com teclado e menu móvel.
- [ ] Bootstrap e recursos funcionam sem internet.
- [ ] README de execução acompanha o código e indica configuração e importação.
- [ ] Configuração empacotada contém apenas valores de exemplo. Credenciais privadas, caches, ficheiros temporários e backups não entram nos ZIP.
- [ ] Foi guardada uma cópia recuperável durante o trabalho, no local autorizado.
- [ ] Consultar as verificações pendentes em 13-AUTOAVALIACAO-CIS.md antes de assumir a entrega como validada no posto.
