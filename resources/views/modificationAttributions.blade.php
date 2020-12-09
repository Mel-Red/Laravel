<!DOCTYPE html>
<html>
   @include("header");
   <body class="hold-transition sidebar-mini layout-fixed">
      <div class="wrapper">

         @include("_debut");

         <!-- Content Wrapper. Contains page content -->
         <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <div class="content-header">
               <div class="container-fluid">
               <div class="row mb-2">
                  <div class="col-sm-6">
                     <h1 class="m-0 text-dark">Attributions chambre</h1>
                  </div><!-- /.col -->
                  <div class="col-sm-6">
                     <ol class="breadcrumb float-sm-right">
                     <li class="breadcrumb-item"><a href="index.php">Accueil</a></li>
                     <li class="breadcrumb-item"><a href="consultationAttributions.php">Consulter</a></li>
                     <li class="breadcrumb-item active">Modifier</li>
                     </ol>
                  </div><!-- /.col -->
               </div><!-- /.row -->
               </div><!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->

            <!-- Main content -->
            <section class="content">
               <div class="container-fluid">
                  @include("_gestionBase.inc.php"); 
                     @include("_controlesEtGestionErreurs.inc.php");
                  <?php
                     ini_set("display_errors","on");
                     error_reporting(E_ALL);

                     

                     // CONNEXION AU SERVEUR MYSQL PUIS SÉLECTION DE LA BASE DE DONNÉES festival

                     $connexion=connect();
                     if (!$connexion)
                     {
                        ajouterErreur("Echec de la connexion au serveur MySql");
                        afficherErreurs();
                        exit();
                     }
                     if (!selectBase($connexion))
                     {
                        ajouterErreur("La base de données festival est inexistante ou non accessible");
                        afficherErreurs();
                        exit();
                     }

                     // EFFECTUER OU MODIFIER LES ATTRIBUTIONS POUR L'ENSEMBLE DES ÉTABLISSEMENTS

                     // CETTE PAGE CONTIENT UN TABLEAU CONSTITUÉ DE 2 LIGNES D'EN-TÊTE (LIGNE TITRE ET 
                     // LIGNE ÉTABLISSEMENTS) ET DU DÉTAIL DES ATTRIBUTIONS 
                     // UNE LÉGENDE FIGURE SOUS LE TABLEAU

                     // Recherche du nombre d'établissements offrant des chambres pour le 
                     // dimensionnement des colonnes
                     $nbEtabOffrantChambres=obtenirNbEtabOffrantChambres($connexion);
                     $nb=$nbEtabOffrantChambres+1;
                     // Détermination du pourcentage de largeur des colonnes "établissements"
                     $pourcCol=50/$nbEtabOffrantChambres;

                     $action=$_REQUEST['action'];

                     // Si l'action est validerModifAttrib (cas où l'on vient de la page 
                     // donnerNbChambres.php) alors on effectue la mise à jour des attributions dans 
                     // la base 
                     if ($action=='validerModifAttrib')
                     {
                        $idEtab=$_REQUEST['idEtab'];
                        $idEquipe=$_REQUEST['idEquipe'];
                        $nbChambres=$_REQUEST['nbChambres'];
                        modifierAttribChamb($connexion, $idEtab, $idEquipe, $nbChambres);
                     }
                     echo "<table class='table table-bordered'>";
                           
                        // AFFICHAGE DE LA 2ÈME LIGNE D'EN-TÊTE (ÉTABLISSEMENTS)
                        echo "<tr class='table-info'>
                                 <td>&nbsp;</td>";
                
                        $req=obtenirReqEtablissementsOffrantChambres();
                        $rsEtab=$connexion->query($req);
                        $lgEtab=$rsEtab->fetch();

                        // Boucle sur les établissements (pour afficher le nom de l'établissement et 
                        // le nombre de chambres encore disponibles)
                        while ($lgEtab!=FALSE)
                        {
                           $idEtab=$lgEtab["idEtab"];
                           $nomEtab=$lgEtab["nomEtab"];
                           $nbOffre=$lgEtab["nombreChambresOffertes"];
                           $nbOccup=obtenirNbOccup($connexion, $idEtab);
                                       
                           // Calcul du nombre de chambres libres
                           $nbChLib = $nbOffre - $nbOccup;
                           echo "<td valign='top' width='$pourcCol%'><i>Disponibilités : $nbChLib </i> <br>
                           $nomEtab </td>";
                           $lgEtab=$rsEtab->fetch();
                        }
                        echo "<td>Nb total chambres réservées</td>";
                        echo "</tr>"; 

                        // CORPS DU TABLEAU : CONSTITUTION D'UNE LIGNE PAR Equipe À HÉBERGER AVEC LES 
                        // CHAMBRES ATTRIBUÉES ET LES LIENS POUR EFFECTUER OU MODIFIER LES ATTRIBUTIONS
                              
                        $req=obtenirReqIdNomEquipesAHeberger();
                        $rsEquipe=$connexion->query($req);
                        $lgEquipe=$rsEquipe->fetch();
                              
                        // BOUCLE SUR LES EquipeS À HÉBERGER 
                        while ($lgEquipe!=FALSE)
                        {
                           $idEquipe=$lgEquipe['idEquipe'];
                           $nomEquipe=$lgEquipe['nomEquipe'];
                           $nomPays=$lgEquipe['nomPays'];
                           echo "<tr>
                                    <td class='table-info' width='25%'>$nomEquipe ($nomPays)</td>";
                           $req=obtenirReqEtablissementsOffrantChambres();
                           $rsEtab=$connexion->query($req);
                           $lgEtab=$rsEtab->fetch();
                              
                           // BOUCLE SUR LES ÉTABLISSEMENTS
                           while ($lgEtab!=FALSE)
                           {
                              $idEtab=$lgEtab["idEtab"];
                              $nbOffre=$lgEtab["nombreChambresOffertes"];
                              $nbOccup=obtenirNbOccup($connexion, $idEtab);
                                       
                              // Calcul du nombre de chambres libres
                              $nbChLib = $nbOffre - $nbOccup;
                                       
                              // On recherche si des chambres ont déjà été attribuées à ce Equipe
                              // dans cet établissement
                              $nbOccupEquipe=obtenirNbOccupEquipe($connexion, $idEtab, $idEquipe);
                              
                              // Cas où des chambres ont déjà été attribuées à ce Equipe dans cet
                              // établissement
                              if ($nbOccupEquipe!=0)
                              {
                                 // Le nombre de chambres maximum pouvant être demandées est la somme 
                                 // du nombre de chambres libres et du nombre de chambres actuellement 
                                 // attribuées au Equipe (ce nombre $nbmax sera transmis si on 
                                 // choisit de modifier le nombre de chambres)
                                 $nbMax = $nbChLib + $nbOccupEquipe;
                                 echo "<td class='reserve' style='text-align:center'>
                                          <a href='donnerNbChambres.php?idEtab=$idEtab&amp;idEquipe=$idEquipe&amp;nbChambres=$nbMax'>
                                 $nbOccupEquipe</a></td>";
                              }
                              else
                              {
                                 // Cas où il n'y a pas de chambres attribuées à ce Equipe dans cet 
                                 // établissement : on affiche un lien vers donnerNbChambres s'il y a 
                                 // des chambres libres sinon rien n'est affiché     
                                 if ($nbChLib != 0)
                                 {
                                    echo "<td class='reserveSiLien' style='text-align:center'>
                                             <a href='donnerNbChambres.php?idEtab=$idEtab&amp;idEquipe=$idEquipe&amp;nbChambres=$nbChLib'>
                                             __</a></td>";
                                 }
                                 else
                                 {
                                    echo "<td class='reserveSiLien'>&nbsp;</td>";
                                 }
                              }
                                  
                              $lgEtab=$rsEtab->fetch();
                           } // Fin de la boucle sur les établissements    
                           $lgEquipe=$rsEquipe->fetch();
                           $nbChambresRes = obtenirNbChambresReserve($connexion, $idEquipe);
                           echo
                           "<td width='5%'>".$nbChambresRes."</td>";
                           echo "</tr>";  
                        } // Fin de la boucle sur les Equipes à héberger   

                     echo "</table>"; // Fin du tableau principal

                     // AFFICHAGE DE LA LÉGENDE
                     echo '<div class="container">
                              <div class="row">
                                 <div class="col-sm">
                                    <a class="btn btn-outline-dark" href="consultationAttributions.php" role="button">Retour</a>
                                 </div>
                                 <div class="col-sm">
                                    <table class="table table-borderless">
                                       <tr>
                                          <td class="reserveSiLien">&nbsp;</td>
                                          <td>Réservation possible si lien</td>
                                       </tr>
                                    </table>
                                 </div>
                                 <div class="col-sm">
                                    <table class="table table-borderless">
                                    <tr>
                                       <td class="reserve">&nbsp;</td>
                                       <td>Chambres réservées</td>
                                    </tr>
                                 </table>
                                 </div>
                              </div>
                           </div>';
                  ?>
               </div>
            </section>
            </br>
            <!-- /.content -->
         </div>
         <!-- /.content-wrapper -->

         @include("_footer");
      </div>
      <!-- ./wrapper -->

   @include("jQuery");
   </body>
</html>
