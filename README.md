
Le repo contient 2 projets tous deux basés sur Symfony7, `product-trial` contient le code de l'API, `test-product-trial` est pour tester l'API

## Installation

Les 2 projets peuvent être installés avec Docker (si vous êtes sur Windows utilisez de préférence WSL, mais sinon ça devrait aussi marcher avec Docker Desktop)

- Cloner le repo `git clone https://github.com/tarik334/product-trial.git`
- Rentrer dans le dossier cloné `cd product-trial`
- `sudo docker compose up -d --build`

## Accès

Après installation les 2 urls d'accès aux projets (depuis navigateur) sont :
- pour `test-product-trial` : http://localhost:8080
- pour `product-trial` : http://localhost:8081

Note : si vous utiliser WSL ça se pourrait que vous devez changer localhost par l'adresse ip WSL

## Tester l'API

Le test de l'API se fait avec le projet `test-product-trial`, et donc en utilisant l'url : http://localhost:8080

### Ajouter des utilisateurs

Les méthodes de l'API ne sont accessibles que par un token, pour pouvoir générer un token il faudrait tt d'abord ajouter des utilisateurs :

url d'ajout d'un utilisateur :
http://localhost:8080/test/account/{username}/{password}/{firstname}/{email}

exemple d'ajout de 2 utilisateurs :
http://localhost:8080/test/account/user/user/user/user@user.com
http://localhost:8080/test/account/admin/admin/admin/admin@admin.com

### Obtenir un token

L'url d'obtention d'un token :
http://localhost:8080/test/token/{email}/{password}

exemple pour un utilisateur :
http://localhost:8080/test/token/user@user.com/user

pour l'admin :
http://localhost:8080/test/token/admin@admin.com/admin

### Méthodes de l'api :

http://localhost:8080/test/products/add/{name}/{description}/{token}
http://localhost:8080/test/products/list/{token}
http://localhost:8080/test/products/get/{id}/{token}
http://localhost:8080/test/products/update/{id}/{name}/{description}/{token}
http://localhost:8080/test/products/delete/{id}/{token}

http://localhost:8080/test/basket/get/{token}
http://localhost:8080/test/basket/add/{id}/{quantity}/{token}
http://localhost:8080/test/basket/delete/{id}/{quantity}/{token}
http://localhost:8080/test/basket/delete/{token}

http://localhost:8080/test/wishlist/get/{token}
http://localhost:8080/test/wishlist/add/{id}/{token}
http://localhost:8080/test/wishlist/delete/{id}/{token}
