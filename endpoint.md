# Liste des endpoints — Backend API OpenDevMada Members

Base URL (exemple):

```text
http://localhost:8000/api/opendevmada
```

Les réponses sont toujours au format JSON. Les en-têtes CORS sont ouverts pour le développement.

---

## 1) Connexion membre

- URL complète: `http://localhost:8000/api/opendevmada/membre-login`
- Méthode HTTP: `POST`
- Headers requis:
  - `Content-Type: application/json`
- Corps (JSON):

  ```json
  {
    "email": "user@example.com",
    "password": "votre_mot_de_passe"
  }
  ```

- Exemple CURL:

  ```bash
  curl -X POST \
    -H "Content-Type: application/json" \
    -d '{"email":"user@example.com","password":"secret"}' \
    http://localhost:8000/api/opendevmada/membre-login
  ```

- Exemple de réponse (200):

  ```json
  {
    "status": "success",
    "message": "Connexion réussie !",
    "membre": {
      "id": 1,
      "nom": "Nom",
      "prenom": "Prenom",
      "role": "admin",
      "photo_profil": "images/Nom/fichier.jpg"
    }
  }
  ```

- Cas particuliers:
  - 400: `{"status":"error","message":"email manquant"}`
  - 401: `{"status":"error","message":"Email ou mot de passe incorrect"}`
  - 500: `{"status":"error","message":"Erreur BDD : ..."}`

---

## 2) Déconnexion membre

- URL complète: `http://localhost:8000/api/opendevmada/membre-logout/{id}`
- Méthode HTTP: `POST`
- Paramètres URL:
  - `id` (integer) — identifiant du membre
- Corps: vide
- Exemple CURL:

  ```bash
  curl -X POST http://localhost:8000/api/opendevmada/membre-logout/12
  ```

- Exemple de réponse (200):

  ```json
  {
    "status": "Succès",
    "message": "Déconnexion réussi !"
  }
  ```

- Cas particuliers:
  - 401: `{"status":"error","message":"erreur interne"}` (en cas d'erreur BDD)

---

## 3) Lister les membres

- URL complète: `http://localhost:8000/api/opendevmada/membres`
- Méthode HTTP: `GET`
- Paramètres: aucun
- Exemple CURL:

  ```bash
  curl http://localhost:8000/api/opendevmada/membres
  ```

- Exemple de réponse (200, liste non vide):

  ```json
  {
    "status": "success",
    "data": [
      {
        "id": 1,
        "nom": "Nom",
        "prenom": "Prenom",
        "email": "user@example.com",
        "role": "admin"
      }
    ]
  }
  ```

- Exemple de réponse si aucun membre:

  ```json
  {
    "status": "success",
    "message": "Aucun membre trouvé",
    "data": []
  }
  ```

---

## 4) Détail d'un membre

- URL complète: `http://localhost:8000/api/opendevmada/membre/{id}`
- Méthode HTTP: `GET`
- Paramètres URL:
  - `id` (integer)
- Exemple CURL:

  ```bash
  curl http://localhost:8000/api/opendevmada/membre/5
  ```

- Exemple de réponse (200):

  ```json
  {
    "status": "success",
    "data": {
      "id": 5,
      "nom": "Nom",
      "prenom": "Prenom",
      "email": "user@example.com",
      "photo_profil": "images/NOM/fichier.jpg",
      "role": "membre",
      "statut": "actif"
    }
  }
  ```

- Cas particuliers:
  - Si non trouvé, renvoie un objet de la forme :

    ```json
    {
      "status": "success",
      "message": "Aucun membre trouvé",
      "data": []
    }
    ```

---

## 5) Créer un membre

- URL complète: `http://localhost:8000/api/opendevmada/membre-create`
- Méthode HTTP: `POST`
- Headers: `Content-Type: multipart/form-data`
- Paramètres (FormData):
  - Requis: `nom` (string), `prenom` (string), `phone` (string), `image` (file)
  - Optionnels: `email` (string), `password` (string), `birthday` (d/m/Y), `sexe` (string), `address` (string), `city` (string), `contry` (string), `role` (string), `statut` (string)
- Exemple CURL:

  ```bash
  curl -X POST http://localhost:8000/api/opendevmada/membre-create \
    -F nom="Doe" \
    -F prenom="John" \
    -F email="john.doe@example.com" \
    -F password="secret" \
    -F birthday="01/01/1990" \
    -F sexe="Male" \
    -F address="Rue X" \
    -F city="Tana" \
    -F contry="Madagascar" \
    -F phone="0340000000" \
    -F role="admin" \
    -F statut="Actif" \
    -F image=@/chemin/vers/image.jpg
  ```

- Exemple de réponse (200):

  ```json
  {
    "status": "success",
    "message": "Membre créé avec succès !",
    "data": {
      "id": 42,
      "nom": "Doe",
      "prenom": "John",
      "photo_profil": "images/Doe/uuid.jpg"
    }
  }
  ```

- Cas particuliers:
  - Champs requis manquants (200): `{"status":"error","message":"Champs requis manquants."}`
  - Erreur BDD (200): `{"status":"error","message":"Erreur BDD : ..."}`

---

## 6) Mettre à jour un membre

- URL complète: `http://localhost:8000/api/opendevmada/membre-update/{id}`
- Méthode HTTP: `POST`
- Headers: `Content-Type: multipart/form-data`
- Paramètres URL:
  - `id` (integer)
- Paramètres (FormData) — tous optionnels:
  - `email`, `password`, `address`, `city`, `phone`, `role`, `statut`, `image` (file)
- Exemple CURL:

  ```bash
  curl -X POST http://localhost:8000/api/opendevmada/membre-update/5 \
    -F email="new.mail@example.com" \
    -F address="Nouvelle adresse" \
    -F image=@/chemin/vers/nouvelle-image.jpg
  ```

- Exemple de réponse (200):

  ```json
  {
    "status": "success",
    "message": "Le membre a été mis à jour !",
    "data": {
      "id": 5,
      "email": "new.mail@example.com",
      "photo_profil": "images/Nom/nouvelle-image.jpg"
    }
  }
  ```

- Cas particuliers:
  - Membre inexistant (200): `{"status":"error","message":"Membre non trouvé."}`
  - Échec upload image (200): `{"status":"error","message":"Échec du téléchargement de l’image."}`
  - Erreur BDD (200): `{"status":"error","message":"Erreur BDD : ..."}`

---

## 7) Supprimer un membre

- URL complète: `http://localhost:8000/api/opendevmada/membre-delete/{id}`
- Méthode HTTP: `DELETE`
- Paramètres URL:
  - `id` (integer)
- Exemple CURL:

  ```bash
  curl -X DELETE http://localhost:8000/api/opendevmada/membre-delete/5
  ```

- Exemple de réponse (200):

  ```json
  {
    "status": "Succès",
    "message": "Suppression d'un membre réussi !"
  }
  ```

- Cas particuliers:
  - Erreur BDD (200): `{"status":"error","message":"Echec de la supprission de donnée"}`

---

## En-têtes CORS

Les en-têtes suivants sont renvoyés (par défaut) pour toutes les routes :

- `Access-Control-Allow-Origin: *`
- `Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS`
- `Access-Control-Allow-Headers: Content-Type`

Configurez `CORS_ORIGINS` dans `.env` pour restreindre les domaines autorisés.
