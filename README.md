# Filament Multi-Tenancy Start Kit

O Filament Multi-Tenancy Start Kit é um projeto base desenvolvido para facilitar a criação de sistemas SaaS multi tenancy, single e multi-database. Ele oferece uma estrutura pronta para gerenciamento de múltiplas empresas, usuários, utilizando o poderoso framework Laravel combinado com o Filament Admin.

## Funcionalidades

-   [x] Multi Tenancy
-   [x] Cadastro de Empresas
-   [x] Cadastro de Usuários
-   [x] ACL
-   [x] Profile edit com avatar
-   [x] Profile edit com Configurações customizadas

### Multi Tenancy

-   Login e Cadastro de Usuários: Sistema de autenticação para usuários com associação às suas respectivas empresas.
-   Cadastro de Empresas: Interface para registro e gerenciamento de empresas com validação integrada.
-   Validação Multi-Tenancy: Separação lógica e segura dos dados de cada empresa no banco de dados.
-   Criação de Tenant em painel separado (master)

## Tecnologias Utilizadas

-   Laravel
-   Filament V3
-   Tenancy for Laravel

## Instalação

#### Composer

```bash
composer install && npm install
```

#### ENV

```bash
 cp .env.example .env
```

#### Key

```bash
php artisan key:generate
```

#### Link Storage

```bash
php artisan storage:link
```

#### Migrações

###### Single-database

```bash
php artisan migrate --seed
```

###### Multi-database

```bash
php artisan migrate:fresh --seeder=LandlordSeeder
```

## Login

#### User

```bash
master@admin.com
```

#### Password

```bash
admin
```
