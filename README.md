# ERP Management System - Clientes & Contratos

Este projeto é um sistema de gestão robusto para administração de clientes, serviços e contratos, focado em alta segurança de dados e performance. Desenvolvido com uma arquitetura moderna que separa as responsabilidades de negócio da infraestrutura.

## 🚀 Funcionalidades Implementadas

- **Gestão de Clientes**: Cadastro, edição e listagem com criptografia de dados sensíveis (LGPD ready).
- **Busca Segura (Blind Index)**: Sistema de busca por documentos criptografados utilizando hashes determinísticos.
- **Gestão de Contratos**: Controle de vigência, valores e itens de serviço.
- **Vínculo de Serviços**: Adição de múltiplos serviços a um contrato com controle de quantidade e valor unitário.
- **Interface Reativa**: Filtros em tempo real com debounce para otimização de requisições.
- **Arquitetura Escalável**: Implementação de padrões de repositório e mappers para desacoplamento de banco de dados.

## 🛠 Tecnologias Utilizadas

- **Backend**: PHP 8.4 + Laravel 11/13
- **Frontend**: Vue 3 + Inertia.js + Tailwind CSS
- **Banco de Dados**: PostgreSQL 14
- **Containerização**: Docker & Docker Compose (PHP-FPM + Nginx)
- **Ícones**: Lucide Vue
- **Utilitários**: Lodash (Debounce), Makefile

## 📦 Como Rodar o Projeto

### Pré-requisitos
- Docker e Docker Compose instalados.
- Make (opcional, mas recomendado).

### Passo a passo

1. **Setup Inicial**:

   O comando abaixo irá construir as imagens, instalar dependências do Composer e NPM, rodar as migrations e gerar a chave da aplicação.
   ```bash
   make setup
   ```

2. **Acessar a Aplicação**:

    Abra o navegador em: http://localhost:8011

3. **Executar Testes**:
    ```bash
   make test
   ```
    **Atenção**: O sistema utiliza criptografia baseada no APP_KEY. Não altere esta chave após popular o banco de dados, ou os documentos dos clientes não poderão ser descriptografados.

4. **Popular o Banco de Dados**:
    ```bash
   make seed
   ```
   **Aviso**: Os dados de CPF/CNPJ introduzidos por esse comando não são reais e não iram passar na validação caso tente atualizar algum outro campo e não ajuste o documento.
