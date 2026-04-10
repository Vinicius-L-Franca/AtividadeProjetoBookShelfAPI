# BookShelf API (Laravel) — Docker Compose

API REST em Laravel para gerenciar **gêneros** e **livros**, com documentação **Swagger UI**.

Este projeto usa **Docker Compose** com:
- **MySQL** (porta host `3307`)
- **PHP/Laravel** (porta host `8000`)

---

## Requisitos

- Docker
- Docker Compose (plugin `docker compose`)

---

## Subir o ambiente

Na raiz do projeto (onde está o `docker-compose.yml`):

```bash
docker compose up -d --build
```

Ver logs (opcional):

```bash
docker compose logs -f
```

---

## Portas / Acessos

### API (Laravel)
- `http://127.0.0.1:8000`

### MySQL
- Host: `127.0.0.1`
- Porta: `3307`
- Database: `laravel`
- User: `laravel`
- Password: `laravel`
- Root password: `rootpassword`

---

## Primeira configuração (dentro do container)

> O serviço PHP no seu compose se chama **`php`**, então todos os comandos abaixo usam `docker compose exec php ...`.

### 1) Instalar dependências (se necessário)
```bash
docker compose exec php composer install
```

### 2) Criar `.env` e gerar APP_KEY
> Seu código está em `./src` (mapeado para `/app` no container).

```bash
docker compose exec php cp .env.example .env
docker compose exec php php artisan key:generate
```

### 3) Configurar DB no `.env`
No arquivo `src/.env`, use **o nome do serviço do MySQL como host** (não `127.0.0.1`):

```env
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=laravel
DB_PASSWORD=laravel
```

Depois limpe cache de config:

```bash
docker compose exec php php artisan optimize:clear
```

### 4) Rodar migrations
```bash
docker compose exec php php artisan migrate
```

---

## Swagger (Documentação)

### Acessar Swagger UI
Com o container `php` rodando:

- `http://127.0.0.1:8000/api/documentation`

### Gerar/atualizar documentação
```bash
docker compose exec php php artisan l5-swagger:generate
```

Sugestão no `src/.env` (ambiente local):
```env
L5_SWAGGER_GENERATE_ALWAYS=true
L5_SWAGGER_OPEN_API_SPEC_VERSION=3.0.0
```

---

## Endpoints (Resumo)

### Gêneros
- `GET /api/genres` — listar gêneros
- `POST /api/genres` — criar gênero
- `GET /api/genres/{id}` — exibir gênero
- `PUT/PATCH /api/genres/{id}` — atualizar gênero
- `DELETE /api/genres/{id}` — excluir gênero

### Livros
- `GET /api/books` — listar livros
- `POST /api/books` — criar livro
- `GET /api/books/{id}` — exibir livro
- `PUT/PATCH /api/books/{id}` — atualizar livro
- `DELETE /api/books/{id}` — excluir livro

---

## Exemplos de Requests (JSON)

### Criar gênero (POST /api/genres)
```json
{
  "name": "Fantasia",
  "description": "Livros de fantasia"
}
```

### Criar livro (POST /api/books)
```json
{
  "genre_id": 1,
  "title": "Clean Code",
  "author": "Robert C. Martin",
  "pages": 464,
  "status": "reading",
  "rating": 5
}
```

---

## Testando com Thunder Client (VS Code)

Como a API está exposta em `8000:8000`, use:

- `GET  http://127.0.0.1:8000/api/genres`
- `POST http://127.0.0.1:8000/api/genres`
- `GET  http://127.0.0.1:8000/api/books`
- `POST http://127.0.0.1:8000/api/books`

No Thunder Client:
- **Body** → `JSON`
- Envie os exemplos acima.

---

## Comandos úteis

Listar rotas da API:
```bash
docker compose exec php php artisan route:list --path=api
```

Limpar caches:
```bash
docker compose exec php php artisan optimize:clear
```

Ver logs do Laravel (dentro do container):
```bash
docker compose exec php sh -lc "tail -n 200 storage/logs/laravel.log"
```

Parar containers:
```bash
docker compose down
```

Remover volumes (APAGA dados do MySQL, cuidado):
```bash
docker compose down -v
```

---

## Troubleshooting rápido

### MySQL não conecta
- No `.env`, use `DB_HOST=mysql` (nome do serviço).
- Garanta que rodou `docker compose up -d` e que o serviço `mysql` está “healthy”/rodando:
  ```bash
  docker compose ps
  ```

### Erro 500 nos endpoints
- Veja o log:
  ```bash
  docker compose exec php sh -lc "tail -n 200 storage/logs/laravel.log"
  ```

---

## Licença
Uso livre para fins educacionais/estudo.
