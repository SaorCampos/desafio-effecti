# Arquitetura da Aplicação

## 🏗 Estrutura e Organização
A aplicação segue uma abordagem inspirada em **Clean Architecture** e **DDD**, organizada nas seguintes camadas dentro de `app/`:

1.  **Domain**: Contém as entidades de negócio e interfaces (contratos) de repositórios. É o núcleo da aplicação, independente de frameworks.
2.  **Application**: Responsável pelos Handlers e casos de uso. Orquestra a lógica de negócio utilizando os repositórios.
3.  **Infrastructure**: Implementações concretas de persistência (Eloquent), Mappers para conversão de Model/Entity e integrações externas.
4.  **Http**: Camada de entrega (Controllers, Requests, Middleware) que lida com a comunicação via Web/API e Inertia.js.

## 🛡 Decisões Técnicas

### 1. Criptografia de Dados (LGPD)
Para atender requisitos de privacidade, o campo `document` (CPF/CNPJ) é armazenado utilizando o cast `encrypted` do Laravel (AES-256-CBC). Isso garante que, em caso de vazamento do banco de dados, os dados sensíveis permaneçam ilegíveis.

### 2. Blind Indexing
Como a criptografia padrão do Laravel é não-determinística (gera hashes diferentes para o mesmo valor), implementamos um **Blind Index**.
- Um hash SHA-256 do documento (com salt da `APP_KEY`) é armazenado na coluna `document_index`.
- Isso permite buscas exatas (`WHERE`) com performance de indexação sem expor o dado original.

### 3. Comunicação via Inertia.js
Utilizamos o **Inertia.js** para manter o fluxo de desenvolvimento de uma SPA (Single Page Application) com a simplicidade do roteamento e controladores do Laravel, eliminando a necessidade de uma API REST complexa para o consumo interno.

### 4. Padrão Repository & Mappers
A camada de persistência é isolada. O `EloquentClientRepository` lida com as queries, e o `ClientMapper` garante que a aplicação receba apenas objetos de domínio, facilitando a troca do banco de dados ou do ORM no futuro, se necessário.

## 💡 Regras de Negócio
- **Validação de Documentos**: Implementada via Form Requests com limpeza de caracteres não numéricos antes da persistência.
- **Status de Contrato**: Lógica baseada em datas de vigência automática.
- **Integridade**: Uso de Database Transactions para criação de contratos e seus itens vinculados.
- **Desconto por Quantidade**: Desconto de 10% caso escolha mais de 10 serviços.
- **Desconto Progressivo**: Se o valor total do contrato exceder R$2000 garante 10% e se exceder R$5000 garante 20%.
- **Regra de Fidelidade**: Para cada ano que o cliente ganha 5% de desconto até 25%.
- **Acréscimo por serviço específico**: Serviços específicos aumentam o valor em 15%.

## 🚀 Melhorias Futuras
- **Busca Parcial em Dados Criptografados**: Implementação de trigramas ou prefixos indexados para permitir `LIKE` em dados sensíveis.
- **Exportação de Relatórios**: Geração de PDFs de contratos utilizando bibliotecas como Snappy ou Browsershot.
- **Logs de Auditoria**: Registro de quem visualizou ou alterou dados sensíveis (Audit Trail).
- **Cache de Queries**: Implementação de Redis para cachear resultados de listagens pesadas de contratos.
