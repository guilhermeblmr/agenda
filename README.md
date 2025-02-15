## Instalação

- Clone o repositório
- Na pasta raiz do projeto, rode `npm install`
- Copie .env.example para dentro de .env
- Execute o comando `docker-compose build`
- Execute o comando `docker-compose up -d`
- Execute o comando `docker exec -it agenda-app bash`para entrar no container
- Dentro do container, execute `composer install`
- Dentro do container, execute `php artisan migrate:fresh`
- Exexute o comando `exit` para sair do container e retornar a pasta raiz do projeto
- Na pasta raiz do projeto, execute `sudo chmod 777 -R storage`, para garantir as permissões necessárias para o projeto
- Na pasta raiz do projeto, rode `npm run dev`

O projeto estará rodando na porta 8000.
