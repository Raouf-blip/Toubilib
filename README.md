# Toubilib - API de Gestion de Rendez-vous Médicaux

API RESTful développée avec PHP/Slim pour la gestion de rendez-vous médicaux entre patients et praticiens.

## Lien vers le dépôt git

https://github.com/Raouf-blip/Toubilib

## Installation et Lancement

### Prérequis
- Docker et Docker Compose
- Git

### Installation
```bash
git clone https://github.com/Raouf-blip/Toubilib
cd Toubilib
# Copier le fichier de configuration
cp app/config/.env.dist app/config/.env
# Lancer les services
docker-compose up -d
```

### Vérification
```bash
curl http://localhost:6080/
```

L'API répondra avec la liste de tous les endpoints disponibles.

## Comptes de Test

**Patients (role=1):**
- Email: `Denis.Teixeira@hotmail.fr` / Mot de passe: `test`
- Email: `Marie.Guichard@sfr.fr` / Mot de passe: `test`

**Praticiens (role=10):**
- Email: `dith.Didier@club-internet.fr` / Mot de passe: `test`
- Email: `radio.plus@sante.fr` / Mot de passe: `test`

### Exemple d'utilisation

```bash
# 1. Se connecter et récupérer le token
TOKEN=$(curl -X POST http://localhost:6080/auth/login \
  -H "Content-Type: application/json" \
  -d '{"email":"Denis.Teixeira@hotmail.fr","mdp":"test"}' \
  -s | jq -r '.token')

# 2. Lister les praticiens
curl -X GET http://localhost:6080/praticiens

# 3. Rechercher des praticiens par spécialité et ville
curl -X GET "http://localhost:6080/praticiens/search?specialite=radiologie&ville=Paris"

# 4. Obtenir les détails d'un praticien
curl -X GET http://localhost:6080/praticiens/{id}

# 5. Créer un RDV
curl -X POST http://localhost:6080/rdvs \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer $TOKEN" \
  -d '{
    "patientId":"{patientId}",
    "praticienId":"{praticienId}",
    "dateHeureDebut":"2026-01-20 10:00:00",
    "duree":30,
    "motifVisite":"radiologie"
  }'
```

## Fonctionnalités Implémentées

| # | Fonctionnalité | Endpoint | Statut |
|---|----------------|----------|--------|
| 1 | Lister les praticiens | `GET /praticiens` | OK |
| 2 | Détail praticien | `GET /praticiens/{id}` | OK |
| 3 | Créneaux occupés | `GET /praticiens/{id}/rdvs/occupes?dateDebut=...&dateFin=...` | OK |
| 4 | Consulter RDV | `GET /rdvs/{id}` | OK |
| 5 | Réserver RDV | `POST /rdvs` | OK |
| 6 | Annuler RDV | `DELETE /rdvs/{id}` | OK |
| 7 | Agenda praticien | `GET /praticiens/{id}/agenda?dateDebut=...&dateFin=...` | OK |
| 8 | Authentification | `POST /auth/login` | OK |
| 9 | Recherche praticiens | `GET /praticiens/search?specialite=...&ville=...` | OK |
| 10 | Marquer RDV honoré/non honoré | `PATCH /rdvs/{id}/honorer` / `PATCH /rdvs/{id}/non-honorer` | OK |
| 11 | Historique consultations patient | `GET /patients/{id}/consultations` | OK |
| 12 | Inscription patient | `POST /auth/register` | OK |
| 13 | Gestion indisponibilités | `POST /praticiens/{id}/indisponibilites`<br>`GET /praticiens/{id}/indisponibilites`<br>`DELETE /praticiens/{id}/indisponibilites/{indisponibiliteId}` | OK |

## Architecture

- **Architecture hexagonale** : Séparation Domain, Application, Infrastructure
- **4 bases PostgreSQL** distinctes (auth, patients, praticiens, rdv)
- **Authentification JWT** avec middlewares d'autorisation par rôle et ressource
- **API RESTful** conforme aux standards REST avec liens HATEOAS
- **Validation des données** via middlewares dédiés
- **Docker** avec docker-compose pour le développement

### Structure du projet

```
app/
├── src/
│   ├── api/              # Couche API (Actions, Middlewares, Routes)
│   ├── application_core/ # Couche Application (Use Cases, DTOs, Services)
│   └── infrastructure/   # Couche Infrastructure (Repositories, DB)
├── config/               # Configuration (DI, Routes, Services)
└── public/              # Point d'entrée de l'application
```

## Configuration

### Variables d'environnement

Le fichier `.env` doit être créé à partir de `.env.dist` et contient :
- **JWT_SECRET** : Clé secrète pour la génération des tokens JWT
- **DB_*** : Configuration des connexions aux bases de données

### Bases de données

L'application utilise 4 bases PostgreSQL distinctes :
- **toubiauth** (port 5433) : Authentification et utilisateurs
- **toubipat** (port 5435) : Données des patients
- **toubiprat** (port 5432) : Données des praticiens
- **toubirdv** (port 5434) : Rendez-vous et indisponibilités

### Accès aux bases de données

Un service Adminer est disponible sur `http://localhost:8080` pour gérer les bases de données.

## Tableau de Bord des Réalisations

### Fonctionnalités Implémentées
- Architecture hexagonale + inversion de dépendances
- API RESTful (URIs, méthodes HTTP, status codes, JSON, HATEOAS)
- Authentification JWT + middlewares d'autorisation
- Validation des données + headers CORS
- Bases de données distinctes + Docker
- Fonctionnalités minimales (1-8) toutes implémentées
- Fonctionnalités avancées (9-13) toutes implémentées

### Réalisations par Membre du Groupe

| Membre | Contributions Principales |
|--------|---------------------------|
| **Noah** | Architecture hexagonale, Authentification JWT, Middlewares |
| **Noah, Arman** | API RESTful, Validation des données, HATEOAS |
| **Noah** | Bases de données, Docker, Tests fonctionnels |
| **Léo** | Home, Authentification |
| **Raouf** | Lister les praticiens, Détail praticien, Créneaux occupés, Consulter RDV, Réserver RDV, Annuler RDV, Agenda praticien |
| **Arman** | Détail praticien, Status, HATEOAS |

### Branches

| Membre | Branche |
|--------|---------------------------|
| **Raouf, Noah, Léo, Arman** | Main |
| **Arman** | Lien-Hateoas |
| **Léo** | Authentification |

## Notes importantes

- Tous les endpoints nécessitant une authentification requièrent un header : `Authorization: Bearer {token}`
- Les tokens JWT expirent après 1 heure
- Les réponses JSON utilisent l'encodage UTF-8 sans échappement (`JSON_UNESCAPED_UNICODE`)
- Toutes les réponses incluent des liens HATEOAS pour la navigation dans l'API
