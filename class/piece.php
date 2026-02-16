<?php
require_once("DataBase.php");

class piece {

    public function ajouter_piece($id_entretien, $nom_piece, $prix_unitaire, $quantite, $fournisseur) {
        $montant = $prix_unitaire * $quantite;

        $insert = DataBase::connect()->prepare('INSERT INTO piece_rechange VALUES
            (NULL, :id_entretien, :nom_piece, :prix_unitaire, :quantite, :montant, :fournisseur)');
        try {
            $insert->execute(array(
                'id_entretien' => $id_entretien,
                'nom_piece' => $nom_piece,
                'prix_unitaire' => $prix_unitaire,
                'quantite' => $quantite,
                'montant' => $montant,
                'fournisseur' => $fournisseur
            ));
        } catch (Exception $e) {
            echo 'Erreur : ' . $e->getMessage();
            return false;
        }
        return true;
    }

    public function supprimer_piece($id_piece) {
        // Get entretien_id before deleting
        $stmt = DataBase::connect()->prepare("SELECT id_entretien FROM piece_rechange WHERE id_piece=:id");
        $stmt->execute(['id' => $id_piece]);
        $row = $stmt->fetch(PDO::FETCH_OBJ);
        $id_entretien = $row ? $row->id_entretien : 0;

        $delete = DataBase::connect()->prepare("DELETE FROM piece_rechange WHERE id_piece=:id");
        $delete->execute(['id' => $id_piece]);
        return $id_entretien;
    }

    public function liste_pieces_entretien($id_entretien) {
        $select = DataBase::connect()->prepare("SELECT * FROM piece_rechange WHERE id_entretien=:id ORDER BY id_piece ASC");
        $select->execute(['id' => $id_entretien]);
        return $select->fetchAll(PDO::FETCH_OBJ);
    }

    public function afficher_pieces_entretien($id_entretien) {
        $pieces = $this->liste_pieces_entretien($id_entretien);
        $total = 0;

        if (count($pieces) > 0) {
            foreach ($pieces as $p) {
                $total += $p->montant;
                echo "<tr>";
                echo "<td>{$p->nom_piece}</td>";
                echo "<td>" . number_format($p->prix_unitaire, 2, ',', ' ') . "</td>";
                echo "<td>{$p->quantite}</td>";
                echo "<td>" . number_format($p->montant, 2, ',', ' ') . "</td>";
                echo "<td>{$p->fournisseur}</td>";
                echo "<td><a href='supprimer_piece.php?id={$p->id_piece}&entretien={$id_entretien}' onclick='if(!confirm(\"Supprimer cette piece?\")) return false;'><i class='fa fa-trash' style='color:#e74c3c;'></i></a></td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='6' style='text-align:center; color:#999;'>Aucune piece de rechange</td></tr>";
        }
        return $total;
    }

    public function total_pieces_montant($id_entretien) {
        $stmt = DataBase::connect()->prepare("SELECT COALESCE(SUM(montant),0) as total FROM piece_rechange WHERE id_entretien=:id");
        $stmt->execute(['id' => $id_entretien]);
        $row = $stmt->fetch(PDO::FETCH_OBJ);
        return $row->total;
    }
}
?>
