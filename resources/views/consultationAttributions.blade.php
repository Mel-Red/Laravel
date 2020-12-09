<!DOCTYPE html>
<html>
   <head>
   <meta charset="utf-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <title>Festival Sp’Or | Ligue</title>
   <!-- Tell the browser to be responsive to screen width -->
   <meta name="viewport" content="width=device-width, initial-scale=1">
   <!-- Font Awesome -->
   <link rel="stylesheet" href="./AdminLTE-3.0.5/plugins/fontawesome-free/css/all.min.css">
   <!-- Ionicons -->
   <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
   <!-- Tempusdominus Bbootstrap 4 -->
   <link rel="stylesheet" href="./AdminLTE-3.0.5/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
   <!-- iCheck -->
   <link rel="stylesheet" href="./AdminLTE-3.0.5/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
   <!-- Theme style -->
   <link rel="stylesheet" href="./AdminLTE-3.0.5/dist/css/adminlte.min.css">
   <!-- overlayScrollbars -->
   <link rel="stylesheet" href="./AdminLTE-3.0.5/plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
   <!-- Google Font: Source Sans Pro -->
   <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
   <!-- Custom CSS -->
   <link href="./css/cssGeneral.css" rel="stylesheet" type="text/css">
   </head>
   <body class="hold-transition sidebar-mini layout-fixed">
      <div class="wrapper">

          @include ("_debut");

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
                     <li class="breadcrumb-item active">Consulter</li>
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

                     $method = $_SERVER['REQUEST_METHOD'];
                     if ($method == 'POST') {
                        $_nbOccupEquipe=$_POST['_nbOccupEquipe'];
                        $_idEquipe=$_POST['_idEquipe'];
                        $_idEtab=$_POST['_idEtab'];
                        $result = modifierAttribChamb($connexion, $_idEtab, $_idEquipe, $_nbOccupEquipe);
                     } 

                     // CONSULTER LES ATTRIBUTIONS DE TOUS LES ÉTABLISSEMENTS

                     // IL FAUT QU'IL Y AIT AU MOINS UN ÉTABLISSEMENT OFFRANT DES CHAMBRES POUR  
                     // AFFICHER LE LIEN VERS LA MODIFICATION
                     $nbEtab=obtenirNbEtabOffrantChambres($connexion);
                     if ($nbEtab!=0)
                     {
                        // echo '
                        // <a class="btn btn-outline-dark" href="modificationAttributions.php?action=demanderModifAttrib" role="button">Effectuer ou modifier les attributions</a>';
                        // echo '</br>';
                        // POUR CHAQUE ÉTABLISSEMENT : AFFICHAGE D'UN TABLEAU COMPORTANT 2 LIGNES 
                        // D'EN-TÊTE ET LE DÉTAIL DES ATTRIBUTIONS
                        $req=obtenirReqEtablissementsAyantChambresAttribuées();
                        $rsEtab=$connexion->query($req);
                        $lgEtab=$rsEtab->fetch();
                        // BOUCLE SUR LES ÉTABLISSEMENTS AYANT DÉJÀ DES CHAMBRES ATTRIBUÉES
                        while($lgEtab!=FALSE)
                        {
                           $idEtab=$lgEtab['idEtab'];
                           $nomEtab=$lgEtab['nomEtab'];

                           $nbOffre=$lgEtab["nombreChambresOffertes"];
                           $nbOccup=obtenirNbOccup($connexion, $idEtab);
                           // Calcul du nombre de chambres libres dans l'établissement
                           $nbChLib = $nbOffre - $nbOccup;

                           echo '<table class="table table-bordered">
                                    <caption style="caption-side:top">'.$nomEtab.' (Offre: '.$nbOffre.' Disponibilités: '.$nbChLib.')</caption>
                                       <thead class="thead-info">
                                          <tr class="table-info">
                                          <th scope="col">Nom Equipe</th>
                                          <th scope="col">Nom pays</th>
                                          <th scope="col">Chambres attribuées</th>
                                          </tr>
                                       </thead>';

                                       //AFFICHAGE DU DÉTAIL DES ATTRIBUTIONS : UNE LIGNE PAR Equipe AFFECTÉ 
                                       // DANS L'ÉTABLISSEMENT 
                                       $req=obtenirReqEquipesEtab($idEtab);
                                       $rsEquipe=$connexion->query($req);
                                       $lgEquipe=$rsEquipe->fetch();

                                       // BOUCLE SUR LES EquipeS (CHAQUE Equipe EST AFFICHÉ EN LIGNE)
                                       while($lgEquipe!=FALSE)
                                       {
                                          $idEquipe=$lgEquipe['idEquipe'];
                                          $nomEquipe=$lgEquipe['nomEquipe'];
                                          $nomPays=$lgEquipe['nomPays'];

                                          echo '<tbody>
                                                   <tr>
                                                      <td width="50%" align="left">'.$nomEquipe.'</td>
                                                      <td width="15%" align="left">'.$nomPays.'</td>';
                                                      $nbOccupEquipe=obtenirNbOccupEquipe($connexion, $idEtab, $idEquipe);
                                                      $nbMax = $nbChLib + $nbOccupEquipe;
                                                      echo'<td width="35%" align="left">
                                                            <form method="POST">
                                                               <select class="custom-select" name="_nbOccupEquipe" onchange="this.form.submit()">
                                                               <option selected>'.$nbOccupEquipe.'</option>';
                                                               for ($i=0; $i<=$nbMax; $i++)
                                                               {
                                                                  echo "<option>$i</option>";
                                                               }
                                                               echo '</select>
                                                               <input type="hidden" value="'.$idEtab.'" name="_idEtab">
                                                               <input type="hidden" value="'.$idEquipe.'" name="_idEquipe">
                                                               <noscript><input type="submit" value="Submit"></noscript>
                                                            </form>
                                                         </td>
                                                      
                                                   </tr>';
                                          echo'</tbody>';
                                             
                                          $lgEquipe=$rsEquipe->fetch();
                                       }
                                       // FIN DE LA BOUCLE SUR LES EQUIPES
                           echo '</table>';
                           $lgEtab=$rsEtab->fetch();
                        }
                     }
                  ?>
               </div>
            </section>
            </br>
            <!-- /.content -->
         </div>
         <!-- /.content-wrapper -->

         @include ("_footer.inc.php");
      </div>
      <!-- ./wrapper -->

      <!-- jQuery -->
      <script src="./AdminLTE-3.0.5/plugins/jquery/jquery.min.js"></script>
      <!-- jQuery UI 1.11.4 -->
      <script src="./AdminLTE-3.0.5/plugins/jquery-ui/jquery-ui.min.js"></script>
      <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
      <script>
      $.widget.bridge('uibutton', $.ui.button)
      </script>
      <!-- Bootstrap 4 -->
      <script src="./AdminLTE-3.0.5/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
      <!-- Tempusdominus Bootstrap 4 -->
      <script src="./AdminLTE-3.0.5/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
      <!-- overlayScrollbars -->
      <script src="./AdminLTE-3.0.5/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
      <!-- AdminLTE App -->
      <script src="./AdminLTE-3.0.5/dist/js/adminlte.js"></script>
   </body>
</html>