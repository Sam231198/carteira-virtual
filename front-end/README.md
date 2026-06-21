# Documentação do Front-end

Interface em Vue 3 para a carteira virtual. Esta documentação descreve a stack, páginas, rotas, serviços de API e como executar o front-end.

## Stack do Front-end

- Vue 3
- TypeScript
- Vite
- Vue Router 5
- Axios
- Bootstrap 5
- BootstrapVueNext

## Estrutura das páginas

### `/login`

- Página de login do usuário.
- Componente principal: `front-end/src/components/CardLogin.vue`
- Valida campos `email` e `senha`.
- Ao fazer login com sucesso, armazena o token em `localStorage` e redireciona para `/`.

### `/cadastro`

- Página de cadastro de novo usuário.
- Componente principal: `front-end/src/components/CardCadastro.vue`
- Valida nome, email e senha.
- Realiza cadastro e, em seguida, login automático se o cadastro for bem-sucedido.

### `/`

- Dashboard principal da carteira.
- Página principal: `front-end/src/views/Home.vue`
- Exibe saldo disponível, ações rápidas e histórico de transações.
- Carrega dados do usuário autenticado via `contaService.getConta()`.
- Busca histórico com `walletService.getTransactions(walletId)`.
- Abre modal para depósito, saque e transferência.

## Rotas e autenticação

- `front-end/src/router/index.ts` define as rotas:
  - `/` — `home`, meta `requiresAuth: true`
  - `/login` — `login`, meta `requiresAuth: false`
  - `/cadastro` — `cadastro`, meta `requiresAuth: false`
- O guard `router.beforeEach` verifica o token em `localStorage`:
  - se a rota exige autenticação e não há token, redireciona para `/login`
  - se o usuário já está logado e tenta acessar `/login`, redireciona para `/`

## Serviços de API

### `front-end/src/services/api.ts`

- Configura o `axios` com base URL padrão `http://localhost:8000/api`.
- Define cabeçalhos `Content-Type` e `Accept`.
- Insere automaticamente o token via `Authorization: Bearer <token>`.
- Trata erros `401` removendo o token e redirecionando para `/login`.

### `front-end/src/services/authService.ts`

- `login(email, password)` — autentica o usuário.
- `cadastro(name, email, password)` — cria nova conta.
- `logout()` — encerra a sessão e remove token de `localStorage`.

### `front-end/src/services/walletService.ts`

- `getWallet(walletId)` — obtem dados da carteira.
- `deposit(dados)` — realiza depósito.
- `withdraw(dados)` — realiza saque.
- `transfer(dados)` — realiza transferência entre carteiras.
- `getTransactions(walletId)` — obtém histórico de transações.

## Como rodar

1. Na raiz do projeto, execute:

```bash
docker compose up --build
```

2. Acesse no navegador:

- Front-end: `http://localhost:5173`

3. Caso rode o front-end localmente fora do Docker:

```bash
cd front-end
npm install
npm run dev
```

4. Se o back-end estiver em outro host ou porta, configure `VITE_API_URL` no arquivo de ambiente ou no shell:

```bash
set VITE_API_URL=http://localhost:8000/api
```

## Observações

- O front-end não contém testes automatizados neste repositório.
- A aplicação mantém o token de sessão no `localStorage`.
- Ao falhar uma operação (depositar, sacar, transferir), erros são exibidos no console e o fluxo mantém o usuário na página.
- O `ModalTrasacao` (`front-end/src/components/ModalTrasacao.vue`) controla inputs de valor e e-mail destinatário para transferência.
