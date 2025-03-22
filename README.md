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
- Lancer Docker **docker-compose up -d --build**

### Accéder aux services
- le site : localhost
- adminer (IHM pour gérer la bd) : localhost:8081
- La bd écoute sur 5432

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


