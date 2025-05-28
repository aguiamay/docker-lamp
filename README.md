## Alguns comandos úteis!

### 
```
docker compose up -d
docker compose up <nome_do_serviço>
docker compose up -d --force-recreate phpmyadmin
docker compose up -d --build
```

### Parar e remover os containers existentes
```
docker compose down
```

### Parar e remover containers, redes e liberar recursos
Com o "-v" remove também os volumes
```
docker compose down --remove-orphans
docker compose down -v --remove-orphans
```
### Restar de todos os containers
```
docker-compose restart
```

### Forçar rebuild sem cache
```
docker compose build --no-cache
```

### Listar imagens
```
docker images
```

### Remover imagens
```
docker rmi <id_da_imagem>
```

### Listar containers mesmo stopados
```
docker ps -a
```

### Remover container
```
docker rm <id_do_container>
```

### Restart container
```
docker compose restart nginx
```

### Executar comandos num container específico
```
docker exec -it php php -v
docker exec -it php chmod -R 775 /var/www/html
```