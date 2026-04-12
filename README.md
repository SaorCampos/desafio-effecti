# 🚀 ERP – Contratos e Serviços

![PHP Version](https://img.shields.io/badge/php-8.4-blue.svg)
![Laravel Version](https://img.shields.io/badge/laravel-13-red.svg)
![License](https://img.shields.io/badge/license-MIT-green.svg)

---
# PT-BR

Este é um ERP de contratos e serviços, construído com **Laravel 13**.

## 📌 Funcionalidades Core
* **Redirecionamento Instantâneo:** Respostas `302` ultra-rápidas com cache em Redis.
* **Analytics Assíncrono:** Coleta de métricas sem impactar o tempo de resposta do usuário.
* **Arquitetura DDD:** Código limpo, testável e desacoplado de infraestrutura.

---

## 🚀 Como Rodar

O projeto utiliza um **Makefile** para automatizar todo o setup via Docker.

1. **Instalação:** `make setup`
2. **Acessar:** `http://localhost:8011`

> 📖 **Quer entender a engenharia por trás desses números?** > Confira o [Guia de Arquitetura e Decisões Técnicas](docs/architecture.md).

> 📘 **Documentação OpenAPI**: O contrato completo da API pode ser visualizado no arquivo [openapi.yaml](docs/openapi.yaml). Você pode colar o conteúdo deste arquivo no [Swagger Editor](https://editor.swagger.io/) para testar a interface.

---

## 🔮 Roadmap
* [ ] **Custom Aliases:** Permitir que o usuário escolha o nome da URL encurtada.
* [ ] **Expiration Dates:** Definir data e hora para o link expirar automaticamente.
* [ ] **Dashboard de Analytics:** Interface gráfica para visualização de cliques, países e dispositivos.
* [ ] **Rate Limit Dinâmico:** Implementar limites por API Key ou IP para evitar abuso.
* [ ] **GRPC Integration:** Para comunicação ainda mais rápida entre microserviços.

---
