Olá! Bem vindo(a)!
Esse projeto, trata-se de um TCC desenvolvido em conjunto à um grupo.

# Rotas da API — Power Lab

## Convenções gerais

- Toda resposta é JSON, `Content-Type: application/json; charset=utf-8`, com acentos legíveis (não escapados em `\uXXXX`).
- Erros sempre no formato `{"error": "mensagem"}`.
- **Limite de requisições**: 60 requisições por minuto, por IP, em **qualquer** rota. Passar disso devolve `429`:
  ```json
  { "error": "Muitas requisições. Tenta de novo em instantes." }
  ```
- **Autenticação**: rotas marcadas como "🔒 Autenticada" exigem o header abaixo, com um token obtido em `/login`:
  ```
  Authorization: Bearer <token>
  ```
  O token expira em 1 hora (padrão) e só autoriza operações no **próprio** usuário — um token do usuário 5 não acessa/altera/exclui o usuário 7, mesmo sendo um token válido.

---

## Raquetes (`/paddles`)

| Método | Rota | O que faz |
|---|---|---|
| GET | `/paddles/get` | Lista todas as raquetes: id, name, descriptions (as 5 descrições de estágio), territory. |
| GET | `/paddles/get/{id}` | Uma raquete específica. `404` se o id não existir. |

---

## Ultimates (`/ultimates`)

| Método | Rota | O que faz |
|---|---|---|
| GET | `/ultimates/get` | Lista todas as Ultimates: id, name, description, sprite (base64), territory. |
| GET | `/ultimates/get/{id}` | Uma Ultimate específica. `404` se não existir. |

---

## Partículas (`/particle`)

| Método | Rota | O que faz |
|---|---|---|
| GET | `/particle/get` | Lista todas as partículas. |
| GET | `/particle/get/{id}` | Uma partícula específica. `404` se não existir. |

*(Controller feito por fora da conversa — confirma os campos exatos que ele devolve.)*

---

## Skins (`/skins`)

| Método | Rota | O que faz |
|---|---|---|
| GET | `/skins/get` | Lista todas as skins: id, name. |
| GET | `/skins/get/{id}` | Uma skin específica. `404` se não existir. |

---

## Combinação Raquete + Skin (`/paddles-skins`, `/paddle`, `/skin`)

| Método | Rota | O que faz |
|---|---|---|
| GET | `/paddles-skins/get` | Lista **todas** as combinações existentes: paddleId, skinId, sprite (o desenho daquela skin naquela raquete). |
| GET | `/paddles-skins/get/{paddleId}/{skinId}` | O sprite de uma combinação específica. `404` se essa raquete+skin não tiver sprite cadastrado. |
| GET | `/paddle/{paddleId}/skins` | Todas as skins que existem pra uma raquete específica. Lista vazia (não `404`) se o id não existir ou se a raquete não tiver nenhuma skin ainda — ver ressalva abaixo. |
| GET | `/skin/{skinId}/paddles` | O inverso: todas as raquetes que têm essa skin. Mesma regra de lista vazia. |

> **Nota**: as duas últimas rotas não diferenciam "ID inválido" de "sem resultados" — ambas devolvem `[]`. Isso foi uma decisão deliberada (são rotas de busca/filtro, não de "achar uma coisa específica"), não um bug.

---

## Caixas (`/boxes`)

| Método | Rota | O que faz |
|---|---|---|
| GET | `/boxes/get` | Lista todas as caixas: id, name, sprite, e `rewards` (lista de `{category, chancePercent}` com a chance real já calculada). |
| GET | `/boxes/get/{id}` | Uma caixa específica. `404` se não existir. |

---

## Fases (`/stages`)

| Método | Rota | O que faz |
|---|---|---|
| GET | `/stages/get` | Lista todas as fases, com raquete bot (+ descrição do estágio + skin aplicada), Ultimate bot, partícula bot, território, dificuldade, tipo de inimigo, objetivo (+ quantidade), recompensa (texto + quantidade + sprite) e os até 3 modificadores (`null` nos slots vazios). |
| GET | `/stages/get/{id}` | Uma fase específica, mesmo formato. `404` se não existir. |

---

## Modificadores (`/modifiers`)

| Método | Rota | O que faz |
|---|---|---|
| GET | `/modifiers/get` | Lista todos os modificadores: id, name, description, sprite. |
| GET | `/modifiers/get/{id}` | Um modificador específico. `404` se não existir. |

---

## Usuários (`/users`, `/login`)

| Método | Rota | Auth | O que faz |
|---|---|---|---|
| GET | `/users/get/{id}` | 🔒 Sim | Um usuário específico, **só** id, name, email (nunca senha ou IP). `401` se o token não for desse mesmo `id`; `404` se o id não existir. |
| POST | `/users/post` | Não | Cria um usuário. Corpo: `{name, email, password}` (IP é pego automaticamente do `REMOTE_ADDR`, não vem no corpo). `400` se faltar campo; `409` se o email já existir. |
| PUT | `/users/put/{id}` | 🔒 Sim | Atualiza **parcialmente** — só os campos enviados no corpo mudam, o resto continua igual. `401`/`404` iguais ao GET; `409` se tentar trocar pra um email já usado. |
| DELETE | `/users/delete/{id}` | 🔒 Sim | Exclui o usuário. `401`/`404` iguais aos de cima. |
| POST | `/login` | Não | Corpo: `{email, password}`. Sucesso devolve `{token, user}`. `401` pra credencial errada. `429` se essa conta levou 10 tentativas erradas nos últimos 15 minutos (bloqueio por força bruta, independente do rate limit global). |

---

## Códigos de status usados no geral

| Código | Quando aparece |
|---|---|
| 200 | Sucesso (GET, PUT, DELETE) |
| 201 | Recurso criado (POST de criação) |
| 400 | Campo obrigatório faltando no corpo |
| 401 | Token ausente/inválido/de outro usuário, ou login com credencial errada |
| 404 | Recurso (ou rota) não encontrado |
| 409 | Conflito — ex: email já cadastrado |
| 429 | Limite de requisições ou de tentativas de login excedido |
| 500 | Erro interno — logado no servidor, nunca mostra detalhe pro cliente |
