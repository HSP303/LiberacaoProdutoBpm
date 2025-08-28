# LiberacaoProdutoBpm

Aplicação **Laravel 12** para gerenciamento de liberações de produtos.  
O sistema registra dados de avaliação, controla itens e cavidades, anexa documentos e gera relatórios em PDF.  
Integra-se à plataforma **Senior** para consulta de empresas e produtos.

---

## ✨ Funcionalidades

- 📦 Cadastro e atualização de liberações de produtos  
- 🧩 Controle de itens e cavidades (com valores mínimos/máximos)  
- 📎 Upload e exclusão de anexos  
- 📄 Emissão de relatório em PDF  
- 🔐 Autenticação (Laravel Breeze)  
- 🌐 Integração via API com a plataforma Senior

---

## ⚙️ Requisitos

| Ferramenta | Versão sugerida |
|------------|-----------------|
| PHP        | 8.2+            |
| Composer   | 2.x             |
| Node.js    | 18+             |
| NPM        | 9+              |
| Banco      | SQLite (padrão) ou outro suportado pelo Laravel |

---

## 🚀 Instalação

1. **Clonar o repositório**
   ```bash
   git clone <url-do-repositorio>
   cd LiberacaoProdutoBpm
   ```

2. **Dependências PHP**
   ```bash
   composer install
   ```

3. **Dependências JavaScript**
   ```bash
   npm install
   ```

4. **Arquivo de configuração**
   ```bash
   cp .env.example .env
   ```
   Configure as variáveis do banco e tokens necessários.

5. **Chave da aplicação**
   ```bash
   php artisan key:generate
   ```

6. **Migrações**
   ```bash
   php artisan migrate
   ```

7. **Ambiente de desenvolvimento**
   ```bash
   composer run dev
   ```
   (Executa `php artisan serve`, fila, logs e Vite simultaneamente)

---

## 🧪 Testes

```bash
php artisan test
```

---

## 📚 Estrutura do Projeto

- `app/Http/Controllers` – Lógica de controle (liberação, itens, cavidades, anexos, relatórios)  
- `app/Models` – Modelos Eloquent (LiberacaoProduto, ItemLiberacao etc.)  
- `routes/web.php` – Rotas HTTP  
- `resources/views` – Templates Blade  
- `database/migrations` – Definições de tabelas  
- `tests/` – Testes automatizados

---

## 📝 Licença

Este projeto é licenciado sob a [MIT License](LICENSE).

