# Gestion Entretien Vehicules

Application web de gestion des entretiens et reparations des vehicules d'une societe. Elle permet de suivre les interventions, les pieces de rechange utilisees avec leurs couts, et de generer des rapports detailles filtrables par periode.

## Fonctionnalites

### Gestion des Vehicules
- Ajout, modification, suppression de vehicules
- Suivi du kilometrage, type (voiture/camion), marque, matricule
- Historique complet des entretiens par vehicule
- Recherche par matricule ou filtrage par type

### Gestion des Entretiens
- Creation d'entretiens avec type (vidange, revision, reparation, pneumatique, freinage, carrosserie, electricite, etc.)
- Ajout dynamique de pieces de rechange (nom, prix unitaire, quantite, fournisseur)
- Calcul automatique du montant total (pieces + main d'oeuvre)
- Suivi du statut : En attente, En cours, Termine
- Recherche par periode, type, vehicule ou statut

### Tableau de Bord
- 4 indicateurs : Total vehicules, Total entretiens, Cout total (CFA), Entretiens en cours
- Graphique d'evolution mensuelle des depenses (Morris.js)
- Tableau cout par vehicule
- Tableau cout par piece de rechange
- Filtrage par periode (date debut / date fin)

### Rapports & Analyses
- Rapport detaille par vehicule avec pourcentage du total
- Rapport par piece de rechange avec barres de progression
- Cout moyen par entretien
- Graphique mensuel des depenses
- Impression des rapports

## Technologies

| Composant | Technologie |
|-----------|------------|
| Backend | PHP 8.x |
| Base de donnees | MySQL (PDO) |
| Frontend | Bootstrap 3, jQuery |
| Graphiques | Morris.js + Raphael.js |
| Icones | Font Awesome 4 |
| Police | Google Fonts (Poppins) |
| Serveur | XAMPP (Apache + MySQL) |

## Installation

### Prerequis
- [XAMPP](https://www.apachefriends.org/) (ou tout serveur Apache + MySQL + PHP 8.x)

### Etapes

1. **Cloner le projet**
```bash
git clone https://github.com/momo19985/gestion-entretien-vehicules.git
```

2. **Placer dans le dossier htdocs**
```
Copier le dossier dans C:\xampp\htdocs\
```

3. **Demarrer XAMPP**
- Lancer Apache et MySQL depuis le panneau de controle XAMPP

4. **Creer la base de donnees**
- Ouvrir phpMyAdmin : http://localhost/phpmyadmin
- Creer une base de donnees nommee `parc` (utf8_general_ci)
- Executer le SQL suivant :

```sql
CREATE TABLE `vehicule` (
    `id_v` INT AUTO_INCREMENT PRIMARY KEY,
    `matricule` VARCHAR(50) NOT NULL,
    `type` VARCHAR(50) NOT NULL,
    `marque` VARCHAR(100) NOT NULL,
    `date_f` VARCHAR(20),
    `kilometrage` INT DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `entretien` (
    `id_entretien` INT AUTO_INCREMENT PRIMARY KEY,
    `id_v` INT NOT NULL,
    `date_entretien` DATE NOT NULL,
    `type_entretien` VARCHAR(100) NOT NULL,
    `description` TEXT,
    `main_oeuvre` DECIMAL(10,2) DEFAULT 0,
    `prestataire` VARCHAR(200),
    `statut` ENUM('en_attente','en_cours','termine') DEFAULT 'en_attente',
    `montant_total` DECIMAL(10,2) DEFAULT 0,
    `jour` VARCHAR(10),
    `mois` VARCHAR(5),
    `annes` VARCHAR(5),
    FOREIGN KEY (`id_v`) REFERENCES `vehicule`(`id_v`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `piece_rechange` (
    `id_piece` INT AUTO_INCREMENT PRIMARY KEY,
    `id_entretien` INT NOT NULL,
    `nom_piece` VARCHAR(200) NOT NULL,
    `prix_unitaire` DECIMAL(10,2) NOT NULL,
    `quantite` INT DEFAULT 1,
    `montant` DECIMAL(10,2) NOT NULL,
    `fournisseur` VARCHAR(200),
    FOREIGN KEY (`id_entretien`) REFERENCES `entretien`(`id_entretien`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `respensable` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `login` VARCHAR(255) NOT NULL,
    `pass` VARCHAR(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
```

5. **Creer un compte administrateur**
- Dans phpMyAdmin, inserer un enregistrement dans la table `respensable`
- Ou utiliser le script `setup_db.php` fourni (a supprimer apres utilisation)

6. **Acceder a l'application**
```
http://localhost/gestion-entretien-vehicules/
```

## Structure du projet

```
gestion-entretien-vehicules/
├── class/
│   ├── DataBase.php        # Connexion PDO MySQL
│   ├── entretien.php       # CRUD entretiens
│   ├── piece.php           # CRUD pieces de rechange
│   ├── rapport.php         # Requetes analytiques (rapports)
│   ├── vehicule.php        # CRUD vehicules
│   ├── user.php            # Authentification
│   ├── ctrl.php            # Validation des champs
│   ├── crypt.php           # Chiffrement OpenSSL
│   ├── main.php            # Inclusion de toutes les classes
│   └── logout.php          # Deconnexion
├── css/
│   ├── animations.css      # Animations et transitions UI
│   ├── xstyle.css          # Styles personnalises
│   ├── sb-admin.css        # Theme admin
│   └── bootstrap.min.css   # Bootstrap 3
├── js/
│   ├── connect.js          # Login AJAX avec animations
│   ├── plugins/morris/     # Graphiques Morris.js
│   └── jquery.js           # jQuery
├── img/                    # Images et icones
├── font-awesome/           # Icones Font Awesome
├── index.php               # Page de connexion
├── accueil.php             # Tableau de bord (dashboard)
├── liste_vehicule.php      # Liste des vehicules
├── ajouter_v.php           # Ajouter un vehicule
├── modifier_v.php          # Modifier un vehicule
├── consulter_v.php         # Fiche vehicule + historique
├── liste_entretien.php     # Liste des entretiens
├── ajouter_entretien.php   # Ajouter entretien + pieces
├── modifier_entretien.php  # Modifier un entretien
├── consulter_entretien.php # Detail entretien + pieces
├── rapport.php             # Page rapports et analyses
├── header.php              # Navbar + sidebar
├── footer.php              # Footer + scroll to top
└── load.php                # Ecran de chargement anime
```

## Captures d'ecran

### Page de connexion
Interface moderne avec fond anime, effets de transition et validation en temps reel.

### Tableau de bord
4 cartes de statistiques, graphique mensuel Morris.js, tableaux de cout par vehicule et par piece.

### Gestion des entretiens
Formulaire avec ajout dynamique de pieces de rechange, calcul automatique des montants.

### Rapports
Analyses detaillees avec barres de progression, filtrage par periode, bouton d'impression.

## Configuration

La connexion a la base de donnees se configure dans `class/DataBase.php` :

```php
$connexion = new PDO('mysql:host=localhost;dbname=parc', 'root', '');
```

## Auteur

**Moctar Hamido**

## Licence

Ce projet est libre d'utilisation a des fins educatives et professionnelles.
