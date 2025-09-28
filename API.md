# API Documentation - OpenDevMada Annuaire (Express)

## Base URL

```text
http://localhost:8000/api/opendevmada
```

## Authentication

* Pas de jeton ni de session serveur. Le frontend conserve les informations renvoyées par l’API.

---

## Endpoints

### 1. Login Membre

* **URL**: `/membre-login`
* **Method**: `POST`
* **Content-Type**: `application/json`
* **Body (JSON)**:

```json
{
  "email": "example@mail.com",
  "password": "your_password"
}
```

* **Response**:

```json
{
  "status": "success",
  "message": "Connexion réussie !",
  "membre": {
    "id": 1,
    "nom": "Nom",
    "prenom": "Prenom",
    "role": "admin",
    "photo_profil": "images/Nom/uuid.jpg"
  }
}
```

---

### 2. Liste des Membres

* **URL**: `/membres`
* **Method**: `GET`
* **Response**:

```json
[
  {
    "id": 1,
    "nom": "Nom",
    "prenom": "Prenom",
    "email": "example@mail.com",
    "role": "admin",
    ...
  }
]
```

---

### 3. Détails d'un Membre

* **URL**: `/membre/{id}`
* **Method**: `GET`
* **Response**:

```json
{
  "id": 1,
  "nom": "Nom",
  "prenom": "Prenom",
  "email": "example@mail.com",
  ...
}
```

---

### 4. Déconnexion Membre

* **URL**: `/membre-logout/{id}`
* **Method**: `POST`
* **Response**:

```json
{
  "status": "Succès",
  "message": "Déconnexion réussi !"
}
```

---

### 5. Créer un Membre

* **URL**: `/membre-create`
* **Method**: `POST`
* **Content-Type**: `multipart/form-data`
* **Body (FormData)**:

  * `nom`: string
  * `prenom`: string
  * `email`: string
  * `password`: string
  * `address`: string
  * `city`: string
  * `phone`: string
  * `role`: string (admin/staff)
  * `statut`: string (Actif/Inactif)
  * `image`: file
* **Response**:

```json
{
  "status": "success",
  "message": "Membre créé avec succès !",
  "data": {
    "id": 42,
    "nom": "Nom",
    "prenom": "Prenom",
    "photo_profil": "images/Nom/uuid.jpg",
    "role": "admin"
  }
}
```

---

### 6. Mettre à jour un Membre

* **URL**: `/membre-update/{id}`
* **Method**: `POST`
* **Content-Type**: `multipart/form-data`
* **Body (FormData)**:

  * Les champs sont facultatifs :

    * `email`, `password`, `address`, `city`, `phone`, `role`, `statut`, `image`
* **Response**:

```json
{
  "status": "success",
  "message": "Le membre a été mis à jour !",
  "data": {
    "id": 5,
    "email": "new.mail@example.com",
    "photo_profil": "images/Nom/uuid.jpg"
  }
}
```

---

### 7. Supprimer un Membre

* **URL**: `/membre-delete/{id}`
* **Method**: `DELETE`
* **Response**:

```json
{
  "status": "Succès",
  "message": "Suppression d'un membre réussi !"
}
```

---

## Remarques Frontend

* Utiliser `FormData` pour toutes les routes qui gèrent des fichiers (image upload).
* Utiliser `application/json` pour les endpoints sans fichiers (login).
* Les réponses sont au format JSON, même en cas d'erreur.

---

## Structure des Réponses d'Erreur

```json
{
  "status": "error",
  "message": "Message d'erreur explicite"
}
```
