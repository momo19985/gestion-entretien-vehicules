<?php
require_once("DataBase.php");

class rapport {

    /**
     * Total depenses sur une periode
     */
    public function total_depenses($date_debut = '', $date_fin = '') {
        $where = "";
        $params = [];
        if (!empty($date_debut)) {
            $where .= " AND date_entretien >= :dd";
            $params['dd'] = $date_debut;
        }
        if (!empty($date_fin)) {
            $where .= " AND date_entretien <= :df";
            $params['df'] = $date_fin;
        }

        $sql = "SELECT COALESCE(SUM(montant_total),0) as total FROM entretien WHERE 1=1" . $where;
        $stmt = DataBase::connect()->prepare($sql);
        $stmt->execute($params);
        $row = $stmt->fetch(PDO::FETCH_OBJ);
        return $row->total;
    }

    /**
     * Nombre d'entretiens sur une periode
     */
    public function total_entretiens($date_debut = '', $date_fin = '') {
        $where = "";
        $params = [];
        if (!empty($date_debut)) {
            $where .= " AND date_entretien >= :dd";
            $params['dd'] = $date_debut;
        }
        if (!empty($date_fin)) {
            $where .= " AND date_entretien <= :df";
            $params['df'] = $date_fin;
        }

        $sql = "SELECT COUNT(*) as total FROM entretien WHERE 1=1" . $where;
        $stmt = DataBase::connect()->prepare($sql);
        $stmt->execute($params);
        $row = $stmt->fetch(PDO::FETCH_OBJ);
        return $row->total;
    }

    /**
     * Cout par vehicule sur une periode
     */
    public function cout_par_vehicule($date_debut = '', $date_fin = '') {
        $where = "";
        $params = [];
        if (!empty($date_debut)) {
            $where .= " AND e.date_entretien >= :dd";
            $params['dd'] = $date_debut;
        }
        if (!empty($date_fin)) {
            $where .= " AND e.date_entretien <= :df";
            $params['df'] = $date_fin;
        }

        $sql = "SELECT v.id_v, v.matricule, v.type, v.marque,
                    COUNT(e.id_entretien) as nb_entretiens,
                    COALESCE(SUM(e.montant_total),0) as cout_total
                FROM vehicule v
                LEFT JOIN entretien e ON v.id_v = e.id_v" . str_replace("AND", "AND", " AND 1=1" . $where) . "
                GROUP BY v.id_v
                ORDER BY cout_total DESC";

        // Fix the query for LEFT JOIN with WHERE
        $sql = "SELECT v.id_v, v.matricule, v.type, v.marque,
                    COUNT(e.id_entretien) as nb_entretiens,
                    COALESCE(SUM(e.montant_total),0) as cout_total
                FROM vehicule v
                LEFT JOIN entretien e ON v.id_v = e.id_v";
        
        if (!empty($date_debut) || !empty($date_fin)) {
            $conditions = [];
            if (!empty($date_debut)) $conditions[] = "e.date_entretien >= :dd";
            if (!empty($date_fin)) $conditions[] = "e.date_entretien <= :df";
            $sql .= " AND " . implode(" AND ", $conditions);
        }

        $sql .= " GROUP BY v.id_v, v.matricule, v.type, v.marque ORDER BY cout_total DESC";

        $stmt = DataBase::connect()->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    /**
     * Cout par type de piece sur une periode
     */
    public function cout_par_piece($date_debut = '', $date_fin = '') {
        $where = "";
        $params = [];
        if (!empty($date_debut)) {
            $where .= " AND e.date_entretien >= :dd";
            $params['dd'] = $date_debut;
        }
        if (!empty($date_fin)) {
            $where .= " AND e.date_entretien <= :df";
            $params['df'] = $date_fin;
        }

        $sql = "SELECT p.nom_piece,
                    SUM(p.quantite) as total_quantite,
                    COALESCE(SUM(p.montant),0) as cout_total
                FROM piece_rechange p
                INNER JOIN entretien e ON p.id_entretien = e.id_entretien
                WHERE 1=1" . $where . "
                GROUP BY p.nom_piece
                ORDER BY cout_total DESC";

        $stmt = DataBase::connect()->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    /**
     * Evolution mensuelle des depenses (pour graphique)
     */
    public function evolution_mensuelle($date_debut = '', $date_fin = '') {
        $where = "";
        $params = [];
        if (!empty($date_debut)) {
            $where .= " AND date_entretien >= :dd";
            $params['dd'] = $date_debut;
        }
        if (!empty($date_fin)) {
            $where .= " AND date_entretien <= :df";
            $params['df'] = $date_fin;
        }

        $sql = "SELECT DATE_FORMAT(date_entretien, '%Y-%m') as mois,
                    COALESCE(SUM(montant_total),0) as montant
                FROM entretien
                WHERE 1=1" . $where . "
                GROUP BY DATE_FORMAT(date_entretien, '%Y-%m')
                ORDER BY mois ASC";

        $stmt = DataBase::connect()->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    /**
     * Entretiens en cours
     */
    public function entretiens_en_cours() {
        $stmt = DataBase::connect()->query("SELECT COUNT(*) as total FROM entretien WHERE statut IN ('en_attente','en_cours')");
        $row = $stmt->fetch(PDO::FETCH_OBJ);
        return $row->total;
    }

    /**
     * Total vehicules
     */
    public function total_vehicules() {
        $stmt = DataBase::connect()->query("SELECT COUNT(*) as total FROM vehicule");
        $row = $stmt->fetch(PDO::FETCH_OBJ);
        return $row->total;
    }
}
?>
