# Carteira Virtual

Aplicação completa de carteira digital com back-end em Laravel 13 e front-end em Vue 3 + TypeScript. O projeto inclui autenticação, saldo, depósito, saque, transferência e histórico de transações.

## Documentação

- Back-end: `back-end/README.md`
- Front-end: `front-end/README.md`

## Tecnologias principais

- Back-end: PHP 8.3, Laravel 13, Sanctum, MySQL 8.1
- Front-end: Vue 3, TypeScript, Vite, Vue Router 5, Axios, Bootstrap 5, BootstrapVueNext
- Contêineres: Docker Compose

## Execução com Docker

1. Na pasta raiz do projeto, execute:

```bash
docker compose up --build
```

2. Aguarde os serviços subirem.

3. Acesse:

- Front-end: `http://localhost:5173`
- Back-end API: `http://localhost:8000/api`

4. No navegador, use a página de cadastro para criar um usuário ou faça login com usuário existente.

## Acesso aos serviços

- API Base: `http://localhost:8000/api`
- Front-end: `http://localhost:5173`

## Rotas principais do front-end

- `/login` — página de login
- `/cadastro` — página de cadastro de usuário
- `/` — dashboard da carteira (requer autenticação)

## Observações

- O front-end envia o token JWT no cabeçalho `Authorization: Bearer <token>` para chamadas autenticadas.
- A API usa autenticação via Sanctum e valida rotas protegidas no back-end.
- Se o token expirar ou estiver ausente, o front-end redireciona para `/login`.
- Para rodar os testes do backend, use `cd back-end && vendor/bin/pest` ou execute o serviço backend do Docker e rode `vendor/bin/pest` dentro do container.