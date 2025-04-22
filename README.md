# DawRestaurant

## Lancement avec docker 

### Config
- Installer docker sur votre système
- Lancer Docker
```bash
  docker-compose up -d --build
```
### Deploiement 
- Branch prod.
- Lancer docker-compose up -d --build
- Si erreur composer, éxécuter ces commandes dans le container de l'api :
  - composer install
  - composer update
  - composer dump-autoload
- Relancer les container : 
  - docker compose down
  - docker compose up -d
