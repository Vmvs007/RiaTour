# Cábula de validações do Módulo 3

O PHP valida cada POST antes da gravação. O HTML5 usa `required`, `min` e os tipos adequados. O JavaScript Vanilla acrescenta feedback para campos com apenas espaços e para o preço zero. Os erros aparecem junto dos campos, associados por `aria-describedby`. Os erros gerais de gravação aparecem no topo. A página conserva os valores introduzidos e mostra o valor enviado junto do erro quando o tipo do campo pode ocultá-lo. Uma gravação válida redireciona e apresenta uma mensagem de sucesso.

| Regra | Código curto | Resultado esperado | Como reutilizar |
| --- | --- | --- | --- |
| 1. Campos obrigatórios | `inputText($_POST, 'name') === ''` | «Ria Serena» é aceite. Texto vazio ou só com espaços é rejeitado. | Aplicar aos nomes, contactos e descrições exigidos. |
| 2. Capacidade positiva | `filter_var($capacity, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]])` | 10 é válido. 0, −1 e 1,5 são inválidos. | Usar para a lotação de veículos ou salas. |
| 3. Duração positiva | `filter_var($minutes, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]])` | 45 minutos são válidos. 0 é inválido. | Usar para sessões com duração em minutos. |
| 4. Preço positivo | `$price = str_replace(',', '.', $input)` e verificar formato e `(float) $price > 0` | «12,50» é válido. «0», «−2» e «12,345» são inválidos. | Adaptar os limites à precisão da coluna DECIMAL. |
| 5. Passageiros positivos | `filter_var($passengers, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]])` | 2 é válido. 0 e 2,5 são inválidos. | Contar bilhetes, unidades ou participantes. |
| 6. Data de passeio | `validDate($date) && $date >= date('Y-m-d')` | Hoje e amanhã são válidos. Ontem e 31 de fevereiro são inválidos. | Usar para agendamentos e definir o fuso horário do negócio. |
| 7. Lotação | `$available = $capacity - $reserved` e `$passengers <= $available` | Com capacidade 10 e 8 lugares ocupados por outras reservas, 2 são aceites e 3 rejeitados. | Bloquear a sessão ou recurso numa transação antes de calcular o saldo. |
| 8. IDs relacionados | `in_array($id, array_map('strval', array_column($options, 'id')), true)` | Um ID presente nas opções é válido. Um ID inexistente ou enviado como lista é rejeitado. | Carregar as opções da base e manter a chave estrangeira. |
| 9. Estados permitidos | `in_array($status, ['pendente', 'confirmada', 'cancelada'], true)` | «confirmada» é válido. «pago» é inválido. | Definir a lista de estados do negócio e aplicar a mesma regra ao formulário. |
| 10. Falhas de base de dados | `catch (PDOException $exception)` e `rollBack()` se houver transação ativa | Uma gravação válida confirma a transação. Uma falha recua, regista o detalhe técnico e mostra uma mensagem legível. | Distinguir conflitos de dados, como uma referência removida, de indisponibilidade do servidor. |

Na lotação contam reservas pendentes e confirmadas. Uma edição exclui a própria reserva antes de somar. Cancelar liberta lugares e reativar volta a validar a capacidade. Reduzir a lotação de um barco ou trocar o barco de um passeio também verifica as reservas existentes.

O email tem ainda validação de formato com `FILTER_VALIDATE_EMAIL`. «ana@example.test» passa e «ana@» apresenta um erro junto do email. Os campos numéricos têm limites compatíveis com as colunas SQL. Na aplicação, a pesquisa usa GET e as alterações usam POST com CSRF.

Nos formulários de passeios e reservas, o HTTP 422 assinala dados inválidos, o 409 assinala conflitos durante a gravação e o 503 assinala falhas de acesso à base. O registo do PHP conserva os detalhes da exceção PDO. O exemplo de JavaScript está em [validation.js](../public/assets/validation.js) e o cálculo concorrente está na [cábula de reservas](08-CABULA-RELACOES-E-RESERVAS.md).
