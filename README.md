# Paris
Application web de paris sur l'Euro 2016.

# Installation

## Ansible

Sur votre poste installer ansible
```
# apt-get install ansible 
```

Ouvrez le fichier 'hosts', et remplacer l'adresse IP par celle de votre serveur.
Ouvrez le fichier 'playbook.yml' et modifier les différentes variables.

Vous pouvez configurer l'application Paris (/app/tools/config.ini) directement dans le fichier playbook.yml (Config Application Paris)

Prérequis : Le serveur doit avoir 'python' d'installer
```
# apt-get install python
```

Puis lancez la commande suivante depuis votre poste :
```
ansible-playbook --ask-pass --ask-become-pass -i hosts playbook.yml
```

Le script va installer la configuration nécessaire pour que l'application foncitonne. 

## Configuration
### Base de données
  Indiquer le chemin absolu vers le fichier SQLite dans app/tools/config.ini section "sqlite" attribut "dbname"
  Lancer l'installation des tables avec le script app/tools/database.php (commande: php app/tools/database.php)

### Site
  Indiquer l'url d'accès à l'application dans app/tools/config.ini section "genral" attribut "root_url" (exemple: https://exemple.com)

### Mails
  Section "mail" vous pouvez renseigner le l'expéditeur, attribut "from" ainsi que le mail de réponse, attribut "reply_to"

### Crowdscores
La récupération des scores se fait par l'API _Crowdscores_. ( https://docs.crowdscores.com/ )
Pour activé cette fonctionnalité il faut compléter le fichier de configuration en indiquant la clé (api_key) ainsi que l'id de la compétition.
Il sera nécessaire de compléter également le champs _crowdscores_id_ la table _Equipe_.

## Config Nginx
L'application fonctionne aussi bien sous Apache2 que Nginx. 
Voici un exemple de configuration sur Nginx:

Les trois paramètres obligatoires sont:
  * La page index (page.php)
  * Le dossier de l'application (jusqu'au dossier view)
  * La réécriture d'url (section location @rewrites)
```
server {
        listen 8888;
        root /var/www/paris/app/view;
        index page.php index.html index.htm;

      	location / {
      	   	set $page_to_view "/page.php";
          	try_files $uri $uri/ @rewrites;
      	}

	      location ~* \.(js|css|png|jpg|jpeg|gif|ico)$ {
            expires 14d;
        }
	
      	location ~ \.php$ {
          	include /etc/nginx/fastcgi_params;
      		  try_files $uri $uri/ @rewrites;
      		  fastcgi_pass unix:/var/run/php5-fpm.sock;
            fastcgi_index index.php;
            fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
      	}

      	location @rewrites {
      		rewrite ^/(.*)/(.*)$ /page.php?page=$1&action=$2;
      		rewrite ^/(.*)$ /page.php?page=$1;
      	}
}
```

# Accéder à l'application
Lors de l'installation de la base de données l'utilisateur admin a été créé.
```
Identifiant: admin@admin
Mot de passe: admin
```
Noubliez pas de changer le mot de passe dans le profil.
