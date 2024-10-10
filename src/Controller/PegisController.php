<?php
// src/Controller/GenresController.php
namespace App\Controller;
use Symfony\Component\HttpFoundation\Response;

require_once 'modele/class.PdoJeux.inc.php';

use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\RedirectController;
use PdoJeux;

class PegisController extends AbstractController
{
 /**
 * fonction pour afficher la liste des genres
 * @param $db
 * @param $idGenreModif positionné si demande de modification
 * @param $idGenreNotif positionné si mise à jour dans la vue
 * @param $notification pour notifier la mise à jour dans la vue
 */
 private function afficherPegis(PdoJeux $db, int $idPegisModif, int $idPegisNotif,
string $notification ) {
//  $tbMembres = $db->getLesGenres(); //
 $tbPegis = $db->getLesPegisComplet();
 return $this->render('lesPegis.html.twig', array(
 'menuActif' => 'Jeux',
 'tbPegis' => $tbPegis,
 'idPegisModif' => $idPegisModif,
 'idPegisNotif' => $idPegisNotif,
 'notification' => $notification
 ));
 }

 #[Route('/pegis', name: 'pegis_afficher')]

 public function index(SessionInterface $session)
 {
 if ($session->has('idUtilisateur')) {
 $db = PdoJeux::getPdoJeux();
 return $this->afficherPegis($db, -1, -1, 'rien');
 } else {
 return $this->render('connexion.html.twig');
 }
 }
 #[Route('/pegis/ajouter', name: 'pegis_ajouter')]

 public function ajouter(SessionInterface $session, Request $request)
 {
 $db = PdoJeux::getPdoJeux();
 if (!empty($request->request->get('txtLibPegis'))) {
   $idPegisNotif = $db->ajouterPegi(
      (int) $request->request->get('txtAgePegi'),  // Convertit en entier
      $request->request->get('txtLibPegis')
  );  
 $notification = 'Ajouté';
 }
 return $this->afficherPegis($db, -1, $idPegisNotif, $notification);
 }
 #[Route('/pegis/demandermodifier', name: 'pegis_demandermodifier')]

 public function demanderModifier(SessionInterface $session, Request $request)
 {
 $db = PdoJeux::getPdoJeux();
 return $this->afficherPegis($db, $request->request->get('txtIdPegis'), -1,
'rien');
 }
 #[Route('/pegis/validermodifier', name: 'pegis_validermodifier')]
 
public function validerModifier(SessionInterface $session, Request $request)
{
    // Vérifier si la session est active
    if (!$session->has('idUtilisateur')) {
        return $this->render('connexion.html.twig'); // Rediriger vers la connexion si pas de session
    }

    // Récupérer les données du formulaire et les traiter
    $db = PdoJeux::getPdoJeux();
    $db->modifierPegi(
        (int) $request->request->get('txtIdPegis'),
        (int) $request->request->get('txtAgePegi'),
        $request->request->get('txtLibPegis')
    );

    // Retourner la vue mise à jour après modification
    return $this->afficherPegis($db, -1, $request->request->get('txtIdPegis'), 'Modifié');
}
 #[Route('/pegis/supprimer', name: 'pegis_supprimer')]
 public function supprimer(SessionInterface $session, Request $request)
 {
    $db = PdoJeux::getPdoJeux();
    $db->supprimerPegi($request->request->get('txtIdPegis'));
    $this->addFlash(
    'success', 'Le Pegis a été supprimé'
    );
    return $this->afficherPegis($db, -1, -1, 'rien');
    }
}
