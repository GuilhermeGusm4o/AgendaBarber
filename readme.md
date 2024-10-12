# Projeto de Agendamento de Compromissos

Este projeto é uma aplicação web para gerenciamento de compromissos, utilizando Next.js 14 com App Router, Symfony como backend, e Nginx como servidor web.

## Estrutura do Projeto

```
/agenda-barber
│
├── /nextjs                       # Aplicação frontend em Next.js
│   ├── /src/app
│   │   ├── /appointments         # Componente para gerenciamento de compromissos
│   │   │   ├── page.js           # Componente principal da página de compromissos
│   │   │   └── style.css         # Estilos específicos da página de compromissos
│   │   ├── /api                  # Manipulação de dados de compromissos
│   │   │   └── appointments.js   # API para requisições relacionadas a compromissos
│   │   └── /layout.js            # Layout global da aplicação
│   ├── /public                   # Arquivos públicos (imagens, favicon, etc.)
│   ├── package.json              # Dependências e scripts do projeto
│   └── Dockerfile                # Dockerfile para configuração da aplicação Next.js
│
├── /symfony                     # Aplicação backend em Symfony
│   ├── /config                  # Configurações gerais do Symfony
│   ├── /migrations              # Migrações de banco de dados
│   │   ├── /TblAppointment.php  # Arquivo de migração da tabela Appointment
│   ├── /src                     # Código fonte da aplicação Symfony
│   │   ├── /Controller          # Controladores para manipulação de requisições
│   │   │   └── AppointmentController.php  # Controlador para compromissos
│   │   └── /Entity              # Entidades do modelo de dados
│   │       └── Appointment.php   # Entidade representando um compromisso
│   ├── /var                     # Diretório para cache e logs do Symfony
│   └── composer.json            # Dependências do Symfony
│   └── Dockerfile               # Dockerfile para configuração da aplicação Symfony
│
└── /nginx                      # Configuração do servidor Nginx
|    └── default.conf             # Arquivo de configuração do Nginx
|__ docker-compose.yml            # Configuração do Docker Compose para orquestração
```

## Tecnologias Utilizadas

- **Next.js 14**: Framework React para construção de aplicações web.
- **Symfony**: Framework PHP para construção de APIs e manipulação de dados.
- **Nginx**: Servidor web para servir a aplicação e gerenciar o tráfego.
- **React**: Biblioteca JavaScript para construção de interfaces de usuário.
- **Tailwind CSS**: Framework CSS para estilização da aplicação.
- **API RESTful**: Estrutura de backend para gerenciar dados de compromissos.

## Como Executar o Projeto

### Pré-requisitos

- Certifique-se de ter o [Node.js](https://nodejs.org/) instalado em sua máquina. Recomenda-se a versão 14 ou superior.
- Certifique-se de ter o [PHP](https://www.php.net/) instalado (preferencialmente versão 8.0 ou superior) e o [Composer](https://getcomposer.org/) para gerenciar as dependências do Symfony.
- Certifique-se de ter o [Docker](https://www.docker.com/) instalado, se desejar executar as aplicações em contêineres.

### Passos para Execução

#### 1. Configuração do Symfony

1. Navegue até o diretório do Symfony:

   ```bash
   cd symfony
   ```

2. Instale as dependências do Symfony:

   ```bash
   composer install
   ```

3. Configure o ambiente:

   Crie um arquivo `.env.local` baseado no `.env` e configure as variáveis de ambiente conforme necessário.

4. Execute o servidor Symfony:

   ```bash
   symfony server:start
   ```

#### 2. Configuração do Next.js

1. Navegue até o diretório do Next.js:

   ```bash
   cd nextjs
   ```

2. Instale as dependências do Next.js:

   ```bash
   npm install
   ```

3. Execute a aplicação Next.js:

   ```bash
   npm run dev
   ```

   A aplicação estará disponível em [http://localhost:3000](http://localhost:3000).

#### 3. Configuração do Nginx

1. Navegue até o diretório do Nginx:

   ```bash
   cd nginx
   ```

2. Certifique-se de que o arquivo de configuração `default.conf` está corretamente configurado para apontar para os diretórios corretos do Symfony e Next.js.

3. Inicie o Nginx:

   ```bash
   sudo service nginx start
   ```

### Estrutura da API

A aplicação Symfony inclui uma API simples para gerenciamento de compromissos:

- **GET /api/appointments**: Recupera todos os compromissos.
- **POST /api/appointments**: Adiciona um novo compromisso.
- **PUT /api/appointments/:id**: Atualiza um compromisso existente.
- **DELETE /api/appointments/:id**: Remove um compromisso.

```

```
