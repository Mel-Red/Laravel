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
                     <h1 class="m-0 text-dark">Liste établissements</h1>
                  </div><!-- /.col -->
                  <div class="col-sm-6">
                     <ol class="breadcrumb float-sm-right">
                     <li class="breadcrumb-item"><a href="index.php">Accueil</a></li>
                     <li class="breadcrumb-item active">Liste établissements</li>
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

                     // FAIRE LES REQUETES
                     $req=obtenirReqEtablissements();
                     $rsEtab=$connexion->query($req);
                     $lgEtab=$rsEtab->fetchAll(PDO::FETCH_ASSOC);
                  ?>
                     <!-- BOUCLE SUR LES ÉTABLISSEMENTS -->
                  </br>
                     <div class="row justify-content-center">
                        <div class="col-auto">
                           <table class="table table-Etab table-hover">
                              <!-- <caption style="caption-side:top;text-align:center">Etablissements</caption>-->
                                    <tbody>
                                    <?php foreach ($lgEtab as $row) {
                                       $nomEtab = $row['nomEtab'];
                                       $idEtab = $row['idEtab'];
                                       echo '<tr>';
                                       echo '<td style="width: 500px;">'.$nomEtab.'
                                             </td>';
                                       echo '<td>
                                                <a href="detailEtablissement.php?idEtab='.$row['idEtab'].'">Voir détail</a>
                                             </td>';
                                       echo '<td>
                                                <a href="modificationEtablissement.php?action=demanderModifEtab&amp; idEtab='.$row['idEtab'].'">Modifier</a>
                                             </td>';
                                       $nbOccup = obtenirNbOccup($connexion, $idEtab);
                                       $nbOffertes = obtenirNbOffertes($connexion, $idEtab);
                                       if ($nbOccup == $nbOffertes)
                                          {
                                             echo '<td>Complet</td>';
                                          }
                                       else if (!existeAttributionsEtab($connexion, $idEtab))
                                          {
                                             echo '<td>
                                                      <a href="suppressionEtablissement.php?action=demanderSupprEtab&amp; idEtab='.$row['idEtab'].'">Supprimer</a>
                                                   </td>';
                                          }
                                       else
                                          {
                                             $req=obtenirReqEtablissementsOffrantChambres();
                                             $rsEtab=$connexion->query($req);
                                             $lgEtab=$rsEtab->fetch();
                                             $nbOccup=obtenirNbOccup($connexion, $idEtab);
                                             $nbChOff=$lgEtab["nombreChambresOffertes"];
                                             $nbChLib = $nbChOff - $nbOccup;
                                             
                                             echo '<td>Chambres disponibles: '.$nbChLib.'</td>';          
                                          }        
                                    }
                                    echo'</table>';
                                    $connexion=NULL;
                                    echo  '<tr>';
                                    echo '<div class="row justify-content-center">
                                             <a class="btn btn-outline-dark" href="creationEtablissement.php?action=demanderCreEtab" role="button">Création établissement</a>
                                          </div>';
                                    echo '</tr>';
                                    ?>
                                    </tbody>
                           </table>
                        </div>
                     </div>
               </div>
            </section>
            <!-- /.content -->
         </div>
         <!-- /.content-wrapper -->

         @include("_footer");

      </div>
      <!-- ./wrapper -->

      @include("jQuery");
   </body>
</html>
