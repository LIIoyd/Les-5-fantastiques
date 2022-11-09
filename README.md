# Les-5-fantastiques

**Atelier MediaPhoto**

---

Composition du groupe :
	- LEBLANC Lilian
	- BOURLON Erwan
	- JAROSZ Léa
	- BLANCHARD Loïc
	- PFLÜGER Julian

---

Lien du Trello :
	- https://trello.com/b/KpD9uCSc/trello

---

Mise en place du projet sur son environnement personnel :

# Docker

1.	Démarer docker

```
docker-compose up
```

2.	Ouvrir un nouveau terminale et accéder au docker

```
docker-compose exec --workdir /app php /bin/bash
```

3.	Installer ou mettre à jour le composer

```
composer install or update
```

# Initialiser la basse de donnée

1.	Migration de la base de données

```
./vendor/bin/doctrine-migrations migrate
```

# Commande utile pour drop ou create la bdd

-	Create la base de donnée

```
./vendor/bin/doctrine-migrations execute --up 'App\Domain\Migrations\Version20221108090538'
```

-	Drop la base de donnée

```
./vendor/bin/doctrine-migrations execute --down 'App\Domain\Migrations\Version20221108090538'
```

# Lien vers l'application

- http://localhost:8080