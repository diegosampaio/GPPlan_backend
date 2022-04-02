
# API Plan
API para gestão de usuários.

## Subir a API
Para testar o projeto, siga os passos descritos abaixo

### Passo a passo
Clone Repositório
```sh
git clone https://github.com/diegosampaio/api_plan.git
```

```sh
cd api_plan
```

Crie o Arquivo .env
```sh
cp .env.example .env
```


Atualize as variáveis de ambiente do arquivo .env
```dosini
APP_NAME=API_Plan
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=nome_que_desejar_db
DB_USERNAME=root
DB_PASSWORD=root
```

Instalar as dependências do projeto
```sh
composer install
```

Gerar a key do projeto Laravel
```sh
php artisan key:generate
```

Criar as tabelas do banco de dados
```sh
php artisan migrate
```

Acesse o projeto
[http://localhost:8000](http://localhost:8000)

Endpoints

[GET] /api/users
Lista todos os usuários cadastrados no Banco de Dados

[POST] /api/users
Realiza o cadastro de um usuário, devem ser informados os seguintes paramêtros:
types:
  Users:
    type: object
    properties:
      name: string
      email: string
      phone: string
      password: string
      photo: file

[GET] /api/users/{idUser}
Lista dados cadastrados de um determinado usuário.

[PUT] /api/users/{idUser}
Realiza a atualização de cadastro de um determinado usuário, podem ser atualizados os seguintes paramêtros:
types:
  Users:
    type: object
    properties:
      name: string
      email: string
      phone: string
      password: string
      photo: file

[DELETE] /api/users/{idUser}
Deleta um determinado usuário.
