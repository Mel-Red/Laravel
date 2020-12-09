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
                     <h1 class="m-0 text-dark">Attribution chambres</h1>
                  </div><!-- /.col -->
                  <div class="col-sm-6">
                     <ol class="breadcrumb float-sm-right">
                     <li class="breadcrumb-item"><a href="index.php">Accueil</a></li>
                     <li class="breadcrumb-item"><a href="consultationAttributions.php">Consulter</a></li>
                     <li class="breadcrumb-item"><a href="modificationAttributions.php?action=demanderModifAttrib">Modifier</a></li>
                     <li class="breadcrumb-item active">Donner chambres</li>
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

                     // SÉLECTIONNER LE NOMBRE DE CHAMBRES SOUHAITÉES

                     $idEtab=$_REQUEST['idEtab'];
                     $idEquipe=$_REQUEST['idEquipe'];
                     $nbChambres=$_REQUEST['nbChambres'];
                     
                     echo '<form method="POST" action="modificationAttributions.php"><center>
                              <input type="hidden" value="validerModifAttrib" name="action">
                              <input type="hidden" value='.$idEtab.' name="idEtab">
                              <input type="hidden" value='.$idEquipe.' name="idEquipe">';
                              $nomEquipe=obtenirNomEquipe($connexion, $idEquipe);

                              echo '<div class="form-group">
                              <label><h5>Combien de chambres souhaitez-vous pour le Equipe '.$nomEquipe.' dans cet établissement ?</h5></label>
                              <select class="form-control" name="nbChambres" id="formDonnerChambres">';
                              
                              for ($i=0; $i<=$nbChambres; $i++)
                              {
                                 echo "<option>$i</option>";
                              }
                              echo '</select>
                                    </br>
                                    <div class="text-center">
                                       <input class="btn btn-outline-info" type="submit" value="Valider" name="valider">
                                       <input class="btn btn-outline-dark" type="reset" value="Annuler" name="Annuler">
                                    </div>
                                    </center>
                           </form>';
                  ?>
               </div>
            </section>
            <!-- /.content -->
         </div>
         <!-- /.content-wrapper -->

         @include ("_footer");

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