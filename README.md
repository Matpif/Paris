# Paris
Application web de paris sur l'Euro 2016.

# Installation
Récupérer les sources de l'application
Configuration de la base de données:
  Indiquer le chemin absolu vers le fichier SQLite dans app/tools/config.ini section "sqlite" attribut "dbname"

Configuration du site:
  Indiquer l'url d'accès à l'application dans app/tools/config.ini section "genral" attribut "root_url" (exemple: https://exemple.com)

Configuration des mails:
  Section "mail" vous pouvez renseigner le l'expéditeur, attribut "from" ainsi que le mail de réponse, attribut "reply_to"

Lancer l'installation des tables avec le script app/tools/database.php (commande: php app/tools/database.php)

L'application fonctionne aussi bien sous Apache2 que Nginx
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
