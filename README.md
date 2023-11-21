# INSTALLATION DU PROJET EN LOCAL

1-Pour installer le Front en local il faudra au préalable avoir symfony et composer installer sur sa machine puis cloner mon 
repo qui est sur ce lien : https://github.com/DanielAkim2000/GarageParrotBackEnd.git

2-Une fois que c'est lancer la commande: composer update
Qui installera toutes les dependances dont le projet aura besoin

3-Ensuite aller dans le dossier config/packages/nelmio_cors.yaml
  Et changer la valeur de allow_orign par ['*']

4-Ensuite aller dans le fichier .env et changer les infos au niveau de DATABASE_URL pour y mettre votre user et mot de passe ,la version pour PostgreSQL

# Creation de la bdd choix 1

1-Une fois que c'est fait vous pouvez soit creer la base de données avec la commande: php bin/console d:d:c 
2-Et ensuite appliquer ses migrations avec : php bin/console doctrine:migrations:migrate  
Ce qui creera les tables dans la bdd crée dans l'etape 1
3-Si vous passer par cette methode il faudra aussi lancer cette commande qui vous permettra d'inserer dans la table jour_semaine les differents jour de la semaine: php bin/console app:insert-jours-semaine

# Creation de la bdd choix 2

Ou vous pouvez directement recuperer le script present sur ce repo : https://github.com/DanielAkim2000/SqlGarageParrot
et lancer le script depuis le terminal psql qui creera directement la bdd et vous inserera les tables et remplira la table jour semaine avec les jours de lundi a dimanche 

# Creation de l'user ADMIN

Pour pouvoir créer un utilisateur avec le role admin
1-Vous devez utiliser la commande que j’ai mise en place qui est : php bin/console app:insert-user admin@example.com Admin User Password 
En remplacant admin@example.com par celle que vous voulez, 
Admin par le nom que vous voulez  
User par le prenom que vous voulez  
Password par le mot de passe que vous voulez ensuite lancer la commande et l’insertion en bdd sera faite







