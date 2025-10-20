# 🏥 Toubilib - API de Gestion de Rendez-vous Médicaux

API RESTful développée avec PHP/Slim pour la gestion de rendez-vous médicaux entre patients et praticiens.

## Lien vers le dépôt git

https://github.com/Raouf-blip/Toubilib

## 🚀 Installation et Lancement

### Prérequis
- Docker et Docker Compose
- Git

### Installation
```bash
git clone [URL_DU_REPO]
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

## 🧪 Tests

### Comptes de Test
**Patients (role=1):**
- Email: `Denis.Teixeira@hotmail.fr` / Mot de passe: `test`
- Email: `Marie.Guichard@sfr.fr` / Mot de passe: `test`

**Praticiens (role=10):**
- Email: `dith.Didier@club-internet.fr` / Mot de passe: `test`
- Email: `radio.plus@sante.fr` / Mot de passe: `test`

### Exemple de Test
```bash
# 1. Se connecter
TOKEN=$(curl -X POST http://localhost:6080/auth/login \
  -H "Content-Type: application/json" \
  -d '{"email":"Denis.Teixeira@hotmail.fr","mdp":"test"}' \
  -s | grep -o '"token":"[^"]*"' | cut -d'"' -f4)

# 2. Lister les praticiens
curl -X GET http://localhost:6080/praticiens

# 3. Créer un RDV
curl -X POST http://localhost:6080/rdvs \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer $TOKEN" \
  -d '{
    "patientId":"d975aca7-50c5-3d16-b211-cf7d302cba50",
    "praticienId":"4305f5e9-be5a-4ccf-8792-7e07d7017363",
    "dateHeureDebut":"2026-01-20 10:00:00",
    "duree":30,
    "motifVisite":"radiologie"
  }'
```

## 📋 Fonctionnalités Implémentées

| # | Fonctionnalité | Endpoint | Statut |
|---|----------------|----------|--------|
| 1 | Lister les praticiens | `GET /praticiens` | ✅ |
| 2 | Détail praticien | `GET /praticiens/{id}` | ✅ |
| 3 | Créneaux occupés | `GET /praticiens/{id}/rdvs/occupes` | ✅ |
| 4 | Consulter RDV | `GET /rdvs/{id}` | ✅ |
| 5 | Réserver RDV | `POST /rdvs` | ✅ |
| 6 | Annuler RDV | `DELETE /rdvs/{id}` | ✅ |
| 7 | Agenda praticien | `GET /praticiens/{id}/agenda` | ✅ |
| 8 | Authentification | `POST /auth/login` | ✅ |

## 🏗️ Architecture

- **Architecture hexagonale** : Domain, Application, Infrastructure
- **4 bases PostgreSQL** distinctes (auth, patients, praticiens, rdv)
- **Authentification JWT** avec middlewares d'autorisation
- **API RESTful** avec liens HATEOAS
- **Docker** avec docker-compose

## 🔧 Configuration

### JWT Secret
Le JWT Secret est configuré dans `app/config/.env`.


## 📊 Tableau de Bord des Réalisations

### Fonctionnalités Implémentées
- ✅ Architecture hexagonale + inversion de dépendances
- ✅ API RESTful (URIs, méthodes HTTP, status codes, JSON, HATEOAS)
- ✅ Authentification JWT + middlewares d'autorisation
- ✅ Validation des données + headers CORS
- ✅ Bases de données distinctes + Docker
- ✅ Fonctionnalités minimales (1-8) toutes implémentées

### Réalisations par Membre du Groupe

| Membre | Contributions Principales |
|--------|---------------------------|
| **Noah** | Architecture hexagonale, Authentification JWT, Middlewares |
| **Noah, Arman** | API RESTful, Validation des données, HATEOAS |
| **Noah** | Bases de données, Docker, Tests fonctionnels |
| **Léo** | Home |
| **Raouf** | Lister les praticiens |
| **Raouf, Arman** | Détail praticien |
| **Raouf** | Créneaux occupés |
| **Raouf** | Consulter RDV |
| **Raouf** | Réserver RDV |
| **Raouf** | Annuler RDV |
| **Raouf** | Agenda praticien |
| **Noah, Léo** | Authentification |
| **Arman** | Status |
| **Noah, Raouf, Arman** | Readme |

### Branche

| Membre | Branche |
|--------|---------------------------|
| **Raouf, Noah, Léo, Arman** | Main |
| **Arman** | Lien-Hateoas |
| **Authentification** | Léo |