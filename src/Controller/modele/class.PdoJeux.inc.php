<?php

use function PHPSTORM_META\type;

/**
 *  AGORA
 * 	©  Logma, 2019
 * @package default
 * @author MD
 * @version    1.0
 * @link       http://www.php.net/manual/fr/book.pdo.php
 * 
 * Classe d'accès aux données. 
 * Utilise les services de la classe PDO
 * pour l'application AGORA
 * Les attributs sont tous statiques,
 * $monPdo de type PDO 
 * $monPdoJeux qui contiendra l'unique instance de la classe
 */
class PdoJeux
{

    private static $monPdo;
    private static $monPdoJeux = null;

    /**
     * Constructeur privé, crée l'instance de PDO qui sera sollicitée
     * pour toutes les méthodes de la classe
     */
    private function __construct()
    {
        // A) >>>>>>>>>>>>>>>   Connexion au serveur et à la base
        try {
            // encodage
            $options = array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\'');
            // Crée une instance (un objet) PDO qui représente une connexion à la base
            PdoJeux::$monPdo = new
            PDO($_ENV['AGORA_DSN'],$_ENV['AGORA_DB_USER'],$_ENV['AGORA_DB_PWD'], $options);
            // PDO::ERRMODE_EXCEPTION: émet une exception 
            PdoJeux::$monPdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            // configure l'attribut ATTR_DEFAULT_FETCH_MODE pour définir le mode de récupération par défaut 
            // PDO::FETCH_OBJ: retourne un objet anonyme avec les noms de propriétés 
            //     qui correspondent aux noms des colonnes retournés dans le jeu de résultats
            PdoJeux::$monPdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
        } catch (PDOException $e) {    // $e est un objet de la classe PDOException, il expose la description du problème
            die('<section id="main-content"><section class="wrapper"><div class = "erreur">Erreur de connexion à la base de données !<p>'
                . $e->getmessage() . '</p></div></section></section>');
        }
    }

    /**
     * Destructeur, supprime l'instance de PDO  
     */
    public function _destruct()
    {
        PdoJeux::$monPdo = null;
    }

    /**
     * Fonction statique qui crée l'unique instance de la classe
     * Appel : $instancePdoJeux = PdoJeux::getPdoJeux();
     * 
     * @return l'unique objet de la classe PdoJeux
     */
    public static function getPdoJeux()
    {
        if (PdoJeux::$monPdoJeux == null) {
            PdoJeux::$monPdoJeux = new PdoJeux();
        }
        return PdoJeux::$monPdoJeux;
    }

    //==============================================================================
    //
    //	METHODES POUR LA GESTION DES GENRES
    //
    //==============================================================================

    /**
     * Retourne tous les genres sous forme d'un tableau d'objets 
     * 
     * @return array le tableau d'objets  (Genre)
     */

    // public function getLesGenres(): array
    // {
    //     $requete =  'SELECT idGenre as identifiant, libGenre as libelle 
	// 					FROM genre 
	// 					ORDER BY libGenre';
    //     try {
    //         $resultat = PdoJeux::$monPdo->query($requete);
    //         $tbGenres  = $resultat->fetchAll();
    //         return $tbGenres;
    //     } catch (PDOException $e) {
    //         die('<div class = "erreur">Erreur dans la requête !<p>'
    //             . $e->getmessage() . '</p></div>');
    //     }
    // }
    public function getLesGenresComplet()
{
$requete = 'SELECT G.idGenre as identifiant, G.libGenre as libelle,
(SELECT COUNT(refJeu) FROM jeu_video AS J WHERE J.idGenre =
G.idGenre) AS nbJeux
FROM genre AS G
ORDER BY G.libGenre';
try {
$resultat = PdoJeux::$monPdo->query($requete);
$tbGenres = $resultat->fetchAll();
return $tbGenres;
} catch (PDOException $e) {
die('<div class = "erreur">Erreur dans la requête !<p>' . $e->getmessage() .
'</p></div>');
}
}

    /**
     * Retourne tous les Pegis sous forme d'un tableau d'objets 
     * 
     * @return array le tableau d'objets  (Genre)
     */
    public function getLesPegisComplet(): array
    {
        $requete =  'SELECT idPegi as identifiant, ageLimite as age, descPegi AS description
						FROM pegi 
						ORDER BY idPegi';
        try {
            $resultat = PdoJeux::$monPdo->query($requete);
            $tbPegis  = $resultat->fetchAll();
            return $tbPegis;
        } catch (PDOException $e) {
            die('<div class = "erreur">Erreur dans la requête !<p>'
                . $e->getmessage() . '</p></div>');
        }
    }



    /**
     * Retourne tous les Plateformes sous forme d'un tableau d'objets 
     * 
     * @return array le tableau d'objets  (Genre)
     */
    public function getLesPlateformes(): array
    {
        $requete =  'SELECT idPlateforme as identifiant, libPlateforme as libelle 
                      FROM plateforme
                      ORDER BY libPlateforme';
        try {
            $resultat = PdoJeux::$monPdo->query($requete);
            $tbPlateformes  = $resultat->fetchAll();
            return $tbPlateformes;
        } catch (PDOException $e) {
            die('<div class = "erreur">Erreur dans la requête !<p>'
                . $e->getmessage() . '</p></div>');
        }
    }
    public function getLesPlateformesComplet(): array
    {
    $requete = 'SELECT G.idPlateforme as identifiant, G.libPlateforme as libelle,
(SELECT COUNT(refJeu) FROM jeu_video AS J WHERE J.idPlateforme = G.idPlateforme) AS nbJeux
FROM Plateforme AS G
ORDER BY G.libPlateforme';
        try {
            $resultat = PdoJeux::$monPdo->query($requete);
            $tbPlateformes  = $resultat->fetchAll();
            return $tbPlateformes;
        } catch (PDOException $e) {
            die('<div class = "erreur">Erreur dans la requête !<p>'
                . $e->getmessage() . '</p></div>');
        }
    }

    /**
     * Retourne tous les Plateformes sous forme d'un tableau d'objets 
     * 
     * @return array le tableau d'objets  (Genre)
     */
    public function getLesMarques(): array
    {
        $requete =  'SELECT idMarque as identifiant, nomMarque as libelle 
                      FROM Marque
                      ORDER BY nomMarque';
        try {
            $resultat = PdoJeux::$monPdo->query($requete);
            $tbMarques  = $resultat->fetchAll();
            return $tbMarques;
        } catch (PDOException $e) {
            die('<div class = "erreur">Erreur dans la requête !<p>'
                . $e->getmessage() . '</p></div>');
        }
    }
    public function getLesMarquesComplet(): array
    {
        $requete = 'SELECT G.idMarque as identifiant, G.nomMarque as libelle,
(SELECT COUNT(refJeu) FROM jeu_video AS J WHERE J.idMarque = G.idMarque) AS nbJeux
FROM Marque AS G
ORDER BY G.nomMarque';
        try {
            $resultat = PdoJeux::$monPdo->query($requete);
            $tbMarques  = $resultat->fetchAll();
            return $tbMarques;
        } catch (PDOException $e) {
            die('<div class = "erreur">Erreur dans la requête !<p>'
                . $e->getmessage() . '</p></div>');
        }
    }

    /**
     * Retourne tous les Plateformes sous forme d'un tableau d'objets 
     * 
     * @return array le tableau d'objets  (Genre)
     */
    public function getLesJeux(): array
    {
        // $requete =  'SELECT refJeu as identifiant, nom as libelle, idPlateforme,idPegi,idGenre,idMarque,prix,dateParution 
        //               FROM jeu_video
        //               ORDER BY dateParution';

        $requete =  'SELECT d.refJeu as identifiant, d.nom as libelle, d.idPlateforme,d.idPegi,d.idGenre,d.idMarque,d.prix,d.dateParution, g.libGenre, g.idGenre , p.libPlateforme, p.idPlateforme, pe.idPegi,pe.ageLimite, pe.descPegi, m.idMarque, m.nomMarque
                      FROM jeu_video As d INNER JOIN genre As g ON d.idGenre = g.idGenre INNER join plateforme As p ON d.idPlateforme = p.idPlateforme INNER JOIN pegi As pe ON d.idPegi = pe.idPegi INNER JOIN marque As m ON d.idMarque = m.idMarque
                      ORDER BY dateParution';

        $marques = PdoJeux::getPdoJeux()->getLesMarques();
        $pegi = PdoJeux::getPdoJeux()->getLesPegis();
        $genres = PdoJeux::getPdoJeux()->getLesGenres();
        $plateformes = PdoJeux::getPdoJeux()->getLesPlateformes();

        try {
            $resultat = PdoJeux::$monPdo->query($requete);
            $tbJeux  = $resultat->fetchAll();
            return [$tbJeux, $plateformes, $pegi, $genres, $marques];
        } catch (PDOException $e) {
            die('<div class = "erreur">Erreur dans la requête !<p>'
                . $e->getmessage() . '</p></div>');
        }
    }
    public function getLesJeuxComplet(): array
    {
        // $requete =  'SELECT refJeu as identifiant, nom as libelle, idPlateforme,idPegi,idGenre,idMarque,prix,dateParution 
        //               FROM jeu_video
        //               ORDER BY dateParution';

        $requete =  'SELECT d.refJeu as identifiant, d.nom as libelle, d.idPlateforme,d.idPegi,d.idGenre,d.idMarque,d.prix,d.dateParution, g.libGenre, g.idGenre , p.libPlateforme, p.idPlateforme, pe.idPegi,pe.ageLimite, pe.descPegi, m.idMarque, m.nomMarque
                      FROM jeu_video As d INNER JOIN genre As g ON d.idGenre = g.idGenre INNER join plateforme As p ON d.idPlateforme = p.idPlateforme INNER JOIN pegi As pe ON d.idPegi = pe.idPegi INNER JOIN marque As m ON d.idMarque = m.idMarque
                      ORDER BY dateParution';

        try {
            $resultat = PdoJeux::$monPdo->query($requete);
            $tbJeux  = $resultat->fetchAll();
            return $tbJeux;
        } catch (PDOException $e) {
            die('<div class = "erreur">Erreur dans la requête !<p>'
                . $e->getmessage() . '</p></div>');
        }
    }

    /**
     * Ajoute un nouveau genre avec le libellé donné en paramètre
     * 
     * @param string $libGenre : le libelle du genre à ajouter
     * @return int l'identifiant du genre crée
     */
    public function ajouterGenre(string $libGenre): int
    {
        try {
            $requete_prepare = PdoJeux::$monPdo->prepare("INSERT INTO genre "
                . "(idGenre, libGenre) "
                . "VALUES (0, :unLibGenre) ");
            $requete_prepare->bindParam(':unLibGenre', $libGenre, PDO::PARAM_STR);
            $requete_prepare->execute();
            // récupérer l'identifiant crée
            return PdoJeux::$monPdo->lastInsertId();
        } catch (Exception $e) {
            die('<div class = "erreur">Erreur dans la requête !<p>'
                . $e->getmessage() . '</p></div>');
        }
    }

    /**
     * Ajoute un nouveau Plateforme avec le libellé donné en paramètre
     * 
     * @param string $libPlateforme : le libelle du Plateforme à ajouter
     * @return int l'identifiant du Plateforme crée
     */
    public function ajouterPlateforme(string $libPlateforme): int
    {
        try {
            $requete_prepare = PdoJeux::$monPdo->prepare("INSERT INTO Plateforme "
                . "(idPlateforme, libPlateforme) "
                . "VALUES (0, :unLibPlateforme) ");
            $requete_prepare->bindParam(':unLibPlateforme', $libPlateforme, PDO::PARAM_STR);
            $requete_prepare->execute();
            // récupérer l'identifiant crée
            return PdoJeux::$monPdo->lastInsertId();
        } catch (Exception $e) {
            die('<div class = "erreur">Erreur dans la requête !<p>'
                . $e->getmessage() . '</p></div>');
        }
    }

    /**
     * Ajoute un nouveau Marque avec le libellé donné en paramètre
     * 
     * @param string $libMarque : le libelle du Marque à ajouter
     * @return int l'identifiant du Marque crée
     */
    public function ajouterMarque(string $libMarque): int
    {
        try {
            $requete_prepare = PdoJeux::$monPdo->prepare("INSERT INTO Marque "
                . "(idMarque, nomMarque) "
                . "VALUES (0, :unNomMarque) ");
            $requete_prepare->bindParam(':unNomMarque', $libMarque, PDO::PARAM_STR);
            $requete_prepare->execute();
            // récupérer l'identifiant crée
            return PdoJeux::$monPdo->lastInsertId();
        } catch (Exception $e) {
            die('<div class = "erreur">Erreur dans la requête !<p>'
                . $e->getmessage() . '</p></div>');
        }
    }

    /**
     * Ajoute un nouveau Pegi avec le libellé donné en paramètre
     * 
     * @param string $libPegi : le libelle du Pegi à ajouter
     * @return int l'identifiant du Pegi crée
     */
    public function ajouterPegi(int $agePegi, string $descPegi): int
    {
        try {
            $requete_prepare = PdoJeux::$monPdo->prepare("INSERT INTO Pegi "
                . "(idPegi, ageLimite,DescPegi) "
                . "VALUES (0, :unAgePegi,:unDescriptionPegi)");
            $requete_prepare->bindParam(':unAgePegi', $agePegi, PDO::PARAM_INT);
            $requete_prepare->bindParam(':unDescriptionPegi', $descPegi, PDO::PARAM_STR);
            $requete_prepare->execute();
            // récupérer l'identifiant crée
            return PdoJeux::$monPdo->lastInsertId();
        } catch (Exception $e) {
            die('<div class = "erreur">Erreur dans la requête !<p>'
                . $e->getmessage() . '</p></div>');
        }
    }

    /**
     * Ajoute un nouveau Jeu avec le libellé donné en paramètre
     * 
     * @param string $libJeu : le libelle du Jeu à ajouter
     * @return int l'identifiant du Jeu crée
     */
    public function ajouterJeu(string $refJeu, int $idPlateforme, int $idPegi, int $idGenre, int $idMarque, string $nomJeu, float $prixJeu, string $date): string
    {
        try {
            $requete_prepare = PdoJeux::$monPdo->prepare("INSERT INTO jeu_video "
                . "VALUES (:unrefJeu, :unIdPlateforme,:unIdPegi,:unIdGenre,:unIdMarque,:unNomJeu,:unPrixJeu,:unDateJeu)");
            $requete_prepare->bindParam(':unrefJeu', $refJeu, PDO::PARAM_STR);
            $requete_prepare->bindParam(':unIdPlateforme', $idPlateforme, PDO::PARAM_INT);
            $requete_prepare->bindParam(':unIdPegi', $idPegi, PDO::PARAM_INT);
            $requete_prepare->bindParam(':unIdGenre', $idGenre, PDO::PARAM_INT);
            $requete_prepare->bindParam(':unIdMarque', $idMarque, PDO::PARAM_INT);
            $requete_prepare->bindParam(':unNomJeu', $nomJeu, PDO::PARAM_STR);
            $requete_prepare->bindParam(':unPrixJeu', $prixJeu, PDO::PARAM_STR);
            $requete_prepare->bindParam(':unDateJeu', $date, PDO::PARAM_STR);
            $requete_prepare->execute();
            echo PdoJeux::$monPdo->lastInsertId();
            // récupérer l'identifiant crée
            return $refJeu;
        } catch (Exception $e) {
            die('<div class = "erreur">Erreur dans la requête !<p>'
                . $e->getmessage() . '</p></div>');
        }
    }


    /**
     *      ie le libellé du genre donné en paramètre
     * 
     * @param int $idGenre : l'identifiant du genre à modifier  
     * @param string $libGenre : le libellé modifié
     */
    public function modifierGenre(int $idGenre, string $libGenre): void
    {
        try {
            $requete_prepare = PdoJeux::$monPdo->prepare("UPDATE genre "
                . "SET libGenre = :unLibGenre "
                . "WHERE genre.idGenre = :unIdGenre");
            $requete_prepare->bindParam(':unIdGenre', $idGenre, PDO::PARAM_INT);
            $requete_prepare->bindParam(':unLibGenre', $libGenre, PDO::PARAM_STR);
            $requete_prepare->execute();
        } catch (Exception $e) {
            die('<div class = "erreur">Erreur dans la requête !<p>'
                . $e->getmessage() . '</p></div>');
        }
    }


    /**
     * Modifie le libellé du Marque donné en paramètre
     * 
     * @param int $idMarque : l'identifiant du Marque à modifier  
     * @param string $libMarque : le libellé modifié
     */
    public function modifierMarque(int $idMarque, string $libMarque): void
    {
        try {
            $requete_prepare = PdoJeux::$monPdo->prepare("UPDATE Marque "
                . "SET nomMarque = :unLibMarque "
                . "WHERE Marque.idMarque = :unIdMarque");
            $requete_prepare->bindParam(':unIdMarque', $idMarque, PDO::PARAM_INT);
            $requete_prepare->bindParam(':unLibMarque', $libMarque, PDO::PARAM_STR);
            $requete_prepare->execute();
        } catch (Exception $e) {
            die('<div class = "erreur">Erreur dans la requête !<p>'
                . $e->getmessage() . '</p></div>');
        }
    }

    /**
     * Modifie l'age et desc du Pegi donné en paramètre
     * 
     * @param int $idPegi : l'identifiant du Pegi à modifier  
     */
    public function modifierPegi(int $idPegi, int $agePegi, string $descriptionPegi): void
    {
        try {
            $requete = "UPDATE pegi "
                . "SET ageLimite = :unAgePegi, descPegi = :unDescriptionPegi "
                . "WHERE Pegi.idPegi = :unIdPegi";

            $requete_prepare = PdoJeux::$monPdo->prepare($requete);
            $requete_prepare->bindParam(':unIdPegi', $idPegi, PDO::PARAM_INT);
            $requete_prepare->bindValue(':unAgePegi', $agePegi, PDO::PARAM_INT);
            $requete_prepare->bindParam(':unDescriptionPegi', $descriptionPegi, PDO::PARAM_STR);
            $requete_prepare->execute();
            print_r(gettype($idPegi));
        } catch (Exception $e) {
            die('<div class = "erreur">Erreur dans la requête !<p><br>' . $requete . "<br>"
                . $e->getmessage() . '</p></div>');
        }
    }


    /**
     * Modifie le libellé du Plateforme donné en paramètre
     * 
     * @param int $idPlateforme : l'identifiant du Plateforme à modifier  
     * @param string $libPlateforme : le libellé modifié
     */
    public function modifierPlateforme(int $idPlateforme, string $libPlateforme): void
    {
        try {
            $requete_prepare = PdoJeux::$monPdo->prepare("UPDATE Plateforme "
                . "SET libPlateforme = :unLibPlateforme "
                . "WHERE Plateforme.idPlateforme = :unIdPlateforme");
            $requete_prepare->bindParam(':unIdPlateforme', $idPlateforme, PDO::PARAM_INT);
            $requete_prepare->bindParam(':unLibPlateforme', $libPlateforme, PDO::PARAM_STR);
            $requete_prepare->execute();
        } catch (Exception $e) {
            die('<div class = "erreur">Erreur dans la requête !<p>'
                . $e->getmessage() . '</p></div>');
        }
    }

    /**
     * Modifie le libellé du Plateforme donné en paramètre
     * 
     * @param string $refJeu : l'identifiant du Plateforme à modifier  
     * @param string $libPlateforme : le libellé modifié
     */
    public function modifierJeu(string $refJeu, int $idPlateforme, int $idPegi, int $idGenre, int $idMarque, string $nomJeu, float $prixJeu, string $date): void
    {
        try {
            $requete = "UPDATE jeu_video "
                . "SET idPlateforme = :unIdPlateforme, "
                . "idPegi = :unIdPegi, "
                . "idGenre = :unIdGenre, "
                . "idMarque = :unIdMarque, "
                . "nom = :unNomJeu, "
                . "prix = :unPrixJeu, "
                . "dateParution = :unDateJeu "
                . "WHERE refJeu = :unrefJeu";
            $requete_prepare = PdoJeux::$monPdo->prepare($requete);
            $requete_prepare->bindParam(':unrefJeu', $refJeu, PDO::PARAM_STR);
            $requete_prepare->bindParam(':unIdPlateforme', $idPlateforme, PDO::PARAM_INT);
            $requete_prepare->bindParam(':unIdPegi', $idPegi, PDO::PARAM_INT);
            $requete_prepare->bindParam(':unIdGenre', $idGenre, PDO::PARAM_INT);
            $requete_prepare->bindParam(':unIdMarque', $idMarque, PDO::PARAM_INT);
            $requete_prepare->bindParam(':unNomJeu', $nomJeu, PDO::PARAM_STR);
            $requete_prepare->bindParam(':unPrixJeu', $prixJeu, PDO::PARAM_STR);
            $requete_prepare->bindParam(':unDateJeu', $date, PDO::PARAM_STR);
            $requete_prepare->execute();
        } catch (Exception $e) {
            echo $requete;
            die('<div class = "erreur">Erreur dans la requête !<p>'
                . $e->getmessage() . '</p></div>');
        }
    }



    /**
     * Supprime le genre donné en paramètre
     * 
     * @param int $idGenre :l'identifiant du genre à supprimer 
     */
    public function supprimerGenre(int $idGenre): void
    {
        try {
            $requete_prepare = PdoJeux::$monPdo->prepare("DELETE FROM genre "
                . "WHERE genre.idGenre = :unIdGenre");
            $requete_prepare->bindParam(':unIdGenre', $idGenre, PDO::PARAM_INT);
            $requete_prepare->execute();
        } catch (Exception $e) {
            die('<div class = "erreur">Erreur dans la requête !<p>'
                . $e->getmessage() . '</p></div>');
        }
    }


    /**
     * Supprime le pegi donné en paramètre
     * 
     * @param int $idPegi :l'identifiant du genre à supprimer 
     */
    public function supprimerPegi(int $idPegi): void
    {
        try {
            $requete_prepare = PdoJeux::$monPdo->prepare("DELETE FROM pegi "
                . "WHERE pegi.idPegi = :unIdPegi");
            $requete_prepare->bindParam(':unIdPegi', $idPegi, PDO::PARAM_INT);
            $requete_prepare->execute();
        } catch (Exception $e) {
            die('<div class = "erreur">Erreur dans la requête !<p>'
                . $e->getmessage() . '</p></div>');
        }
    }

    /**
     * Supprime le Marque donné en paramètre
     * 
     * @param int $idMarque :l'identifiant du Marque à supprimer 
     */
    public function supprimerMarque(int $idMarque): void
    {
        try {
            $requete_prepare = PdoJeux::$monPdo->prepare("DELETE FROM Marque "
                . "WHERE Marque.idMarque = :unIdMarque");
            $requete_prepare->bindParam(':unIdMarque', $idMarque, PDO::PARAM_INT);
            $requete_prepare->execute();
        } catch (Exception $e) {
            die('<div class = "erreur">Erreur dans la requête !<p>'
                . $e->getmessage() . '</p></div>');
        }
    }

    /**
     * Supprime le Plateforme donné en paramètre
     * 
     * @param int $idPlateforme :l'identifiant du Plateforme à supprimer 
     */
    public function supprimerPlateforme(int $idPlateforme): void
    {
        try {
            $requete_prepare = PdoJeux::$monPdo->prepare("DELETE FROM Plateforme "
                . "WHERE Plateforme.idPlateforme = :unIdPlateforme");
            $requete_prepare->bindParam(':unIdPlateforme', $idPlateforme, PDO::PARAM_INT);
            $requete_prepare->execute();
        } catch (Exception $e) {
            die('<div class = "erreur">Erreur dans la requête !<p>'
                . $e->getmessage() . '</p></div>');
        }
    }

    /**
     * Supprime le Plateforme donné en paramètre
     * 
     * @param int $idPlateforme :l'identifiant du Plateforme à supprimer 
     */
    public function supprimerJeu(string $refJeu): void
    {
        try {
            $requete_prepare = PdoJeux::$monPdo->prepare("DELETE FROM jeu_video "
                . "WHERE jeu_video.refJeu = :unRefJeu");
            $requete_prepare->bindParam(':unRefJeu', $refJeu, PDO::PARAM_STR);
            $requete_prepare->execute();
        } catch (Exception $e) {
            die('<div class = "erreur">Erreur dans la requête !<p>'
                . $e->getmessage() . '</p></div>');
        }
    }
    //==============================================================================
    //
    // METHODES POUR LA GESTION DES MEMBRES
    //
    //==============================================================================
    /**
     * Retourne l'identifiant, le nom et le prénom de l'utilisateur correspondant au compte et mdp
     *
     * @param string $compte le compte de l'utilisateur
     * @param string $mdp le mot de passe de l'utilisateur
     * @return ?object l'objet ou null si ce membre n'existe pas
     */
    public function getUnMembre(string $loginMembre, string $mdpMembre): ?object
    {
        try {
            // préparer la requête
            $requete_prepare = PdoJeux::$monPdo->prepare(
                'SELECT idMembre, prenomMembre, nomMembre, mdpMembre, selMembre
 FROM membre
 WHERE loginMembre = :unLoginMembre'
            );
            // associer les valeurs aux paramètres
            $requete_prepare->bindParam(':unLoginMembre', $loginMembre, PDO::PARAM_STR);
            // exécuter la requête
            $requete_prepare->execute();
            // récupérer l'objet
            $utilisateur = $requete_prepare->fetch();
            if ($utilisateur != null) {
                $hashedPassword = hash("sha512", $mdpMembre . $utilisateur->selMembre);
                if ($utilisateur->mdpMembre == $hashedPassword) {
                    return $utilisateur;
                }
            }
            // vérifier le mot de passe
            // le mot de passe transmis par le formulaire est le hash du mot de passe saisi
            // le mot de passe enregistré dans la base doit correspondre au hash du (hash transmis concaténé au sel)
        } catch (PDOException $e) {
            die('<div class = "erreur">Erreur dans la requête !<p>'
                . $e->getmessage() . '</p></div>');
        }
        return null;
    }
    public function getLesMembres()
    {
        $requete = 'SELECT idMembre as identifiant, CONCAT(prenomMembre, " ", nomMembre) AS
                    libelle
                    FROM Membre
                    ORDER BY nomMembre';
        try {
            $resultat = PdoJeux::$monPdo->query($requete);
            $tbMembres = $resultat->fetchAll();
            return $tbMembres;
        } catch (PDOException $e) {
            die('<div class = "erreur">Erreur dans la requête !<p>'
                . $e->getmessage() . '</p></div>');
        }
    }
}
