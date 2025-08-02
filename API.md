# API Documentation - OpenDevMada Annuaire

## Base URL

```
http://localhost:2001/api/opendevmada
```

## Authentication

* No token-based authentication.
* Session is used after login.

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
  "status": "Succès",
  "message": "Connexion réussi !",
  "membre": {
    "id": 1,
    "nom": "Nom",
    "prenom": "Prenom",
    "role": "admin"
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
  "status": "success",
  "message": "Déconnexion réussie"
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
  "message": "Un membre a été créé !"
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
  "message": "Une membre a été mise à jour !"
}
```

---

### 7. Supprimer un Membre

* **URL**: `/membre-delete/{id}`
* **Method**: `DELETE`
* **Response**:

```json
{
  "status": "success",
  "message": "Le membre a été supprimé."
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
