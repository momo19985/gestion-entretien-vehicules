<?php
require_once("DataBase.php");

class vehicule {

    public function liste_vehicule() {
        $select = DataBase::connect()->query("SELECT * FROM vehicule ORDER BY id_v DESC");

        while ($d = $select->fetch(PDO::FETCH_OBJ)) {
            echo "<tr class='animate-fadeInUp'>";
            echo "<td>{$d->id_v}</td>";
            echo "<td>{$d->matricule}</td>";
            echo "<td>{$d->type}</td>";
            echo "<td>{$d->marque}</td>";
            echo "<td>" . number_format($d->kilometrage, 0, ',', ' ') . " km</td>";

            $date_n = date('Y');
            $age = $date_n - $d->date_f;
            if ($age <= 0) $age = '< 1';
            echo "<td>{$age}</td>";

            echo "<td><a href='consulter_v.php?id={$d->id_v}'><i class='fa fa-eye fa-lg' style='color:#3498db;'></i></a></td>";
            echo "<td><a href='modifier_v.php?id={$d->id_v}'><i class='fa fa-edit fa-lg' style='color:#f39c12;'></i></a></td>";
            echo "<td><a href='supprimer_v.php?id={$d->id_v}' onclick='if(!confirm(\"Supprimer ce vehicule?\")) return false;'><i class='fa fa-trash fa-lg' style='color:#e74c3c;'></i></a></td>";
            echo "</tr>";
        }
    }

    public function supprimer_vehicule($id) {
        $delete = DataBase::connect()->prepare("DELETE FROM vehicule WHERE id_v=:id");
        $delete->execute(['id' => $id]);
        return true;
    }

    public function select_vehicule($id) {
        $select = DataBase::connect()->prepare("SELECT * FROM vehicule WHERE id_v=:id");
        $select->execute(['id' => $id]);
        return $select->fetchAll(PDO::FETCH_ASSOC);
    }

    public function ajouter_vehicule($mat, $type, $date_f, $marque, $kilometrage = 0) {
        $insert = DataBase::connect()->prepare('INSERT INTO vehicule VALUES
            (NULL, :matricule, :type, :marque, :date_f, :kilometrage)');
        try {
            $insert->execute(array(
                'matricule' => $mat,
                'type' => $type,
                'date_f' => $date_f,
                'marque' => $marque,
                'kilometrage' => $kilometrage
            ));
        } catch (Exception $e) {
            echo 'Erreur : ' . $e->getMessage();
            return false;
        }
        return true;
    }

    public function modifier_vehicule($id, $mat, $type, $date_f, $marque, $kilometrage = 0) {
        $up = DataBase::connect()->prepare('UPDATE vehicule SET
            matricule=:mat, date_f=:date_f, type=:type, marque=:marque, kilometrage=:kilometrage
            WHERE id_v=:id_v');
        try {
            $up->execute(array(
                'id_v' => $id,
                'mat' => $mat,
                'type' => $type,
                'date_f' => $date_f,
                'marque' => $marque,
                'kilometrage' => $kilometrage
            ));
        } catch (Exception $e) {
            echo 'Erreur : ' . $e->getMessage();
            return false;
        }
        return true;
    }

    public function chercher_vehicule($type) {
        if ($type == "tous") {
            $select = DataBase::connect()->query("SELECT * FROM vehicule ORDER BY id_v DESC");
        } else {
            $select = DataBase::connect()->prepare("SELECT * FROM vehicule WHERE type=:type ORDER BY id_v DESC");
            $select->execute(['type' => $type]);
        }

        if ($select->rowCount() > 0) {
            echo "<table class='table table-responsive table-bordered table-hover'>
                <thead><tr>
                    <th>ID</th><th>Matricule</th><th>Type</th><th>Marque</th><th>Kilometrage</th><th>Age (ans)</th><th>Voir</th><th>Modifier</th><th>Supprimer</th>
                </tr></thead><tbody>";

            while ($d = $select->fetch(PDO::FETCH_OBJ)) {
                $date_n = date('Y');
                $age = $date_n - $d->date_f;
                if ($age <= 0) $age = '< 1';

                echo "<tr>";
                echo "<td>{$d->id_v}</td>";
                echo "<td>{$d->matricule}</td>";
                echo "<td>{$d->type}</td>";
                echo "<td>{$d->marque}</td>";
                echo "<td>" . number_format($d->kilometrage, 0, ',', ' ') . " km</td>";
                echo "<td>{$age}</td>";
                echo "<td><a href='consulter_v.php?id={$d->id_v}'><i class='fa fa-eye fa-lg' style='color:#3498db;'></i></a></td>";
                echo "<td><a href='modifier_v.php?id={$d->id_v}'><i class='fa fa-edit fa-lg' style='color:#f39c12;'></i></a></td>";
                echo "<td><a href='supprimer_v.php?id={$d->id_v}' onclick='if(!confirm(\"Supprimer?\")) return false;'><i class='fa fa-trash fa-lg' style='color:#e74c3c;'></i></a></td>";
                echo "</tr>";
            }
            echo "</tbody></table>";
        } else {
            echo "<br><br><center><h3>Aucun resultat</h3></center>";
        }
    }

    public function chercher_vehicule_matricule($matricule) {
        $select = DataBase::connect()->prepare("SELECT * FROM vehicule WHERE matricule LIKE :mat ORDER BY id_v DESC");
        $select->execute(['mat' => "%$matricule%"]);

        if ($select->rowCount() > 0) {
            echo "<table class='table table-responsive table-bordered table-hover'>
                <thead><tr>
                    <th>ID</th><th>Matricule</th><th>Type</th><th>Marque</th><th>Kilometrage</th><th>Age (ans)</th><th>Modifier</th><th>Supprimer</th>
                </tr></thead><tbody>";

            while ($d = $select->fetch(PDO::FETCH_OBJ)) {
                $date_n = date('Y');
                $age = $date_n - $d->date_f;
                if ($age <= 0) $age = '< 1';

                echo "<tr>";
                echo "<td>{$d->id_v}</td>";
                echo "<td>{$d->matricule}</td>";
                echo "<td>{$d->type}</td>";
                echo "<td>{$d->marque}</td>";
                echo "<td>" . number_format($d->kilometrage, 0, ',', ' ') . " km</td>";
                echo "<td>{$age}</td>";
                echo "<td><a href='modifier_v.php?id={$d->id_v}'><i class='fa fa-edit fa-lg' style='color:#f39c12;'></i></a></td>";
                echo "<td><a href='supprimer_v.php?id={$d->id_v}' onclick='if(!confirm(\"Supprimer?\")) return false;'><i class='fa fa-trash fa-lg' style='color:#e74c3c;'></i></a></td>";
                echo "</tr>";
            }
            echo "</tbody></table>";
        } else {
            echo "<br><br><center><h3>Aucun resultat</h3></center>";
        }
    }

    public function total_vehicule() {
        $select = DataBase::connect()->query("SELECT COUNT(*) as total FROM vehicule");
        $row = $select->fetch(PDO::FETCH_OBJ);
        echo $row->total;
    }

    public function total_voiture() {
        $select = DataBase::connect()->query("SELECT COUNT(*) as total FROM vehicule WHERE type='voiture'");
        $row = $select->fetch(PDO::FETCH_OBJ);
        echo $row->total;
    }

    public function total_camion() {
        $select = DataBase::connect()->query("SELECT COUNT(*) as total FROM vehicule WHERE type='camion'");
        $row = $select->fetch(PDO::FETCH_OBJ);
        echo $row->total;
    }

    public function liste_vehicule_options($selected_id = 0) {
        $select = DataBase::connect()->query("SELECT * FROM vehicule ORDER BY marque ASC");
        while ($d = $select->fetch(PDO::FETCH_OBJ)) {
            $sel = ($d->id_v == $selected_id) ? 'selected' : '';
            echo "<option value='{$d->id_v}' {$sel}>{$d->type} - {$d->marque} - {$d->matricule}</option>";
        }
    }
}
