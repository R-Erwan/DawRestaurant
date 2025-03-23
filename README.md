# DawRestaurant

## Lancement avec docker 

**WARNING** Si il y a des pb pour lancer docker ect, modifié pas les fichiers de config
ou alors fait le bien sur une branche (c'est chiant a faire les config docker)

Mais normalement sa devrait marché nickel, j'ai pris que des images légère et ultra compatible multiplatform
sa pourrais tourner sur un arduino le bordel.

### Config
- Installer docker sur votre système
- 2 fichiers de configurations : **Dockerfile** et **docker-compose.yml**
- 1 Fichier de config pour apache dans **config/000-default.conf**
- Lancer Docker
```bash
  docker-compose up -d --build
```

### Accéder aux services
- le site : localhost

Le container apache est monté sur public, donc pour les redirection de page, les chemins commence depuis le répertoire public/
- adminer (IHM pour gérer la bd) : localhost:8081
- La bd écoute sur 6543 en local

### Configuration pour phpStorm
*Ajouter Docker*
- Dans **File > Settings
- Dans **Build, Execution, Deployment > Docker**
- Cliquer sur + (Add) et choisir Docker, normalement rien de plus a fair.
- Cliquer sur **Apply** puis **Ok**

*Ajouter l'interpreteur PHP basé sur Docker*
- Retourner dans **File > Settings > PHP**
- À côté de CLI Interpreter, clique sur **...** (les trois petits points)
- Clique sur **+** et choisir **From Docker, Vagrant, WSL, etc**
- Sélectionner **Docker** et choisis le moteur que tu as configuré avant.
- Dans Image, mettre **php:8.2-apache** (ou l’image PHP de ton Dockerfile).
- Clique sur **Apply** puis **OK**

*Visualier le service*
En bas a gauche de l'IDE il y a un onglet Services, il doit y avoir Docker et 
Docker-compose: dawrestaurant.


## Database Postgresql
Une fois le docker lancer.
La DB écoute sur le port 5433 de votre machine
### Scripts initialisation
- **init.sql** Pour créer les tables au démarrage du container docker
- **seed.sql** Injecte des données de test dans labd
- Pour éxécuter un script alors que le container est lancé : 
```bash

docker ps
docker exec -i <container_name_or_id> psql -U <postgres_user> -d <database_name> -f /path/to/your/init.sql
docker exec -i db psql -U admin -d daw_db < db/seed.sql
```

**command_utils.sql.txt** 

Contient des query utiles pour fetch des donné dans la bd. Ce n'est pas un fichier a éxécuter directement
juste un mémo.