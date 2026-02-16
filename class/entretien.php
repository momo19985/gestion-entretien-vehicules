<?php
require_once("DataBase.php");

class entretien {

    public function ajouter_entretien($id_v, $date, $type_entretien, $description, $main_oeuvre, $prestataire, $statut) {
        $mois = date("m", strtotime($date));
        $annes = date("Y", strtotime($date));
        $jour = date("d", strtotime($date));

        $insert = DataBase::connect()->prepare('INSERT INTO entretien VALUES
            (NULL, :id_v, :date_entretien, :type_entretien, :description, :main_oeuvre, :prestataire, :statut, :main_oeuvre, :jour, :mois, :annes)');
        try {
            $insert->execute(array(
                'id_v' => $id_v,
                'date_entretien' => $date,
                'type_entretien' => $type_entretien,
                'description' => $description,
                'main_oeuvre' => $main_oeuvre,
                'prestataire' => $prestataire,
                'statut' => $statut,
                'jour' => $jour,
                'mois' => $mois,
                'annes' => $annes
            ));
        } catch (Exception $e) {
            echo 'Erreur : ' . $e->getMessage();
            return false;
        }
        return true;
    }

    public function last_entretien_id() {
        $select = DataBase::connect()->query("SELECT id_entretien FROM entretien ORDER BY id_entretien DESC LIMIT 1");
        $row = $select->fetch(PDO::FETCH_OBJ);
        return $row ? $row->id_entretien : 0;
    }

    public function modifier_entretien($id, $id_v, $date, $type_entretien, $description, $main_oeuvre, $prestataire, $statut) {
        $mois = date("m", strtotime($date));
        $annes = date("Y", strtotime($date));
        $jour = date("d", strtotime($date));

        $up = DataBase::connect()->prepare('UPDATE entretien SET
            id_v=:id_v, date_entretien=:date_entretien, type_entretien=:type_entretien,
            description=:description, main_oeuvre=:main_oeuvre, prestataire=:prestataire,
            statut=:statut, jour=:jour, mois=:mois, annes=:annes
            WHERE id_entretien=:id_entretien');
        try {
            $up->execute(array(
                'id_entretien' => $id,
                'id_v' => $id_v,
                'date_entretien' => $date,
                'type_entretien' => $type_entretien,
                'description' => $description,
                'main_oeuvre' => $main_oeuvre,
                'prestataire' => $prestataire,
                'statut' => $statut,
                'jour' => $jour,
                'mois' => $mois,
                'annes' => $annes
            ));
        } catch (Exception $e) {
            echo 'Erreur : ' . $e->getMessage();
            return false;
        }
        return true;
    }

    public function recalculer_montant($id_entretien) {
        $db = DataBase::connect();
        // Sum pieces
        $stmt = $db->prepare("SELECT COALESCE(SUM(montant),0) as total_pieces FROM piece_rechange WHERE id_entretien=:id");
        $stmt->execute(['id' => $id_entretien]);
        $total_pieces = $stmt->fetch(PDO::FETCH_OBJ)->total_pieces;

        // Get main_oeuvre
        $stmt2 = $db->prepare("SELECT main_oeuvre FROM entretien WHERE id_entretien=:id");
        $stmt2->execute(['id' => $id_entretien]);
        $main_oeuvre = $stmt2->fetch(PDO::FETCH_OBJ)->main_oeuvre;

        $montant_total = $total_pieces + $main_oeuvre;

        $up = $db->prepare("UPDATE entretien SET montant_total=:mt WHERE id_entretien=:id");
        $up->execute(['mt' => $montant_total, 'id' => $id_entretien]);
        return $montant_total;
    }

    public function supprimer_entretien($id) {
        $delete = DataBase::connect()->prepare("DELETE FROM entretien WHERE id_entretien=:id");
        $delete->execute(['id' => $id]);
        return true;
    }

    public function select_entretien($id) {
        $select = DataBase::connect()->prepare("SELECT e.*, v.matricule, v.type, v.marque, v.kilometrage
            FROM entretien e
            INNER JOIN vehicule v ON e.id_v = v.id_v
            WHERE e.id_entretien = :id");
        $select->execute(['id' => $id]);
        return $select->fetchAll(PDO::FETCH_ASSOC);
    }

    public function liste_entretien() {
        $select = DataBase::connect()->query("SELECT e.*, v.matricule, v.type, v.marque
            FROM entretien e
            INNER JOIN vehicule v ON e.id_v = v.id_v
            ORDER BY e.id_entretien DESC
            LIMIT 100");

        while ($d = $select->fetch(PDO::FETCH_OBJ)) {
            $statut_class = '';
            $statut_label = '';
            if ($d->statut == 'termine') {
                $statut_class = 'label-success';
                $statut_label = 'Termine';
            } elseif ($d->statut == 'en_cours') {
                $statut_class = 'label-warning';
                $statut_label = 'En cours';
            } else {
                $statut_class = 'label-danger';
                $statut_label = 'En attente';
            }

            echo "<tr class='animate-fadeInUp'>";
            echo "<td>{$d->id_entretien}</td>";
            echo "<td>{$d->date_entretien}</td>";
            echo "<td>{$d->type} - {$d->marque} - {$d->matricule}</td>";
            echo "<td>{$d->type_entretien}</td>";
            echo "<td><span class='label {$statut_class}'>{$statut_label}</span></td>";
            echo "<td>" . number_format($d->montant_total, 2, ',', ' ') . "</td>";
            echo "<td><a href='consulter_entretien.php?id={$d->id_entretien}'><i class='fa fa-eye fa-lg' style='color:#3498db;'></i></a></td>";
            echo "<td><a href='modifier_entretien.php?id={$d->id_entretien}'><i class='fa fa-edit fa-lg' style='color:#f39c12;'></i></a></td>";
            echo "<td><a href='supprimer_entretien.php?id={$d->id_entretien}' onclick='if(!confirm(\"Supprimer cet entretien?\")) return false;'><i class='fa fa-trash fa-lg' style='color:#e74c3c;'></i></a></td>";
            echo "</tr>";
        }
    }

    public function chercher_entretien($date_debut, $date_fin, $type, $vehicule, $statut) {
        $where = " WHERE 1=1";
        $params = [];

        if (!empty($date_debut)) {
            $where .= " AND e.date_entretien >= :date_debut";
            $params['date_debut'] = $date_debut;
        }
        if (!empty($date_fin)) {
            $where .= " AND e.date_entretien <= :date_fin";
            $params['date_fin'] = $date_fin;
        }
        if (!empty($type)) {
            $where .= " AND e.type_entretien LIKE :type";
            $params['type'] = "%$type%";
        }
        if (!empty($vehicule)) {
            $where .= " AND e.id_v = :vehicule";
            $params['vehicule'] = $vehicule;
        }
        if (!empty($statut)) {
            $where .= " AND e.statut = :statut";
            $params['statut'] = $statut;
        }

        $sql = "SELECT e.*, v.matricule, v.type, v.marque
            FROM entretien e
            INNER JOIN vehicule v ON e.id_v = v.id_v"
            . $where . " ORDER BY e.id_entretien DESC";

        $select = DataBase::connect()->prepare($sql);
        $select->execute($params);

        if ($select->rowCount() > 0) {
            echo "<table class='table table-responsive table-bordered table-hover'>
                <thead><tr>
                    <th>ID</th><th>Date</th><th>Vehicule</th><th>Type</th><th>Statut</th><th>Montant</th><th>Voir</th><th>Modifier</th><th>Supprimer</th>
                </tr></thead><tbody>";

            while ($d = $select->fetch(PDO::FETCH_OBJ)) {
                $statut_class = '';
                $statut_label = '';
                if ($d->statut == 'termine') {
                    $statut_class = 'label-success';
                    $statut_label = 'Termine';
                } elseif ($d->statut == 'en_cours') {
                    $statut_class = 'label-warning';
                    $statut_label = 'En cours';
                } else {
                    $statut_class = 'label-danger';
                    $statut_label = 'En attente';
                }
                echo "<tr>";
                echo "<td>{$d->id_entretien}</td>";
                echo "<td>{$d->date_entretien}</td>";
                echo "<td>{$d->type} - {$d->marque} - {$d->matricule}</td>";
                echo "<td>{$d->type_entretien}</td>";
                echo "<td><span class='label {$statut_class}'>{$statut_label}</span></td>";
                echo "<td>" . number_format($d->montant_total, 2, ',', ' ') . "</td>";
                echo "<td><a href='consulter_entretien.php?id={$d->id_entretien}'><i class='fa fa-eye fa-lg' style='color:#3498db;'></i></a></td>";
                echo "<td><a href='modifier_entretien.php?id={$d->id_entretien}'><i class='fa fa-edit fa-lg' style='color:#f39c12;'></i></a></td>";
                echo "<td><a href='supprimer_entretien.php?id={$d->id_entretien}' onclick='if(!confirm(\"Supprimer?\")) return false;'><i class='fa fa-trash fa-lg' style='color:#e74c3c;'></i></a></td>";
                echo "</tr>";
            }
            echo "</tbody></table>";
        } else {
            echo "<br><br><center><h3>Aucun resultat</h3></center>";
        }
    }

    public function total_entretien() {
        $select = DataBase::connect()->query("SELECT COUNT(*) as total FROM entretien");
        $row = $select->fetch(PDO::FETCH_OBJ);
        echo $row->total;
    }

    public function total_entretien_encours() {
        $select = DataBase::connect()->query("SELECT COUNT(*) as total FROM entretien WHERE statut IN ('en_attente','en_cours')");
        $row = $select->fetch(PDO::FETCH_OBJ);
        echo $row->total;
    }

    public function cout_total() {
        $select = DataBase::connect()->query("SELECT COALESCE(SUM(montant_total),0) as total FROM entretien");
        $row = $select->fetch(PDO::FETCH_OBJ);
        echo number_format($row->total, 2, ',', ' ');
    }

    public function cout_total_value() {
        $select = DataBase::connect()->query("SELECT COALESCE(SUM(montant_total),0) as total FROM entretien");
        $row = $select->fetch(PDO::FETCH_OBJ);
        return $row->total;
    }

    // Get list of vehicules for select dropdown
    public function liste_vehicule_options($selected_id = 0) {
        $select = DataBase::connect()->query("SELECT * FROM vehicule ORDER BY marque ASC");
        while ($d = $select->fetch(PDO::FETCH_OBJ)) {
            $sel = ($d->id_v == $selected_id) ? 'selected' : '';
            echo "<option value='{$d->id_v}' {$sel}>{$d->type} - {$d->marque} - {$d->matricule}</option>";
        }
    }
}
?>
