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
                     <h1 class="m-0 text-dark">Suppression établissement</h1>
                  </div><!-- /.col -->
                  <div class="col-sm-6">
                     <ol class="breadcrumb float-sm-right">
                     <li class="breadcrumb-item"><a href="index.php">Accueil</a></li>
                     <li class="breadcrumb-item"><a href="listeEtablissements.php">Liste établissement</a></li>
                     <li class="breadcrumb-item active">Suppression établissement</li>
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

                     // SUPPRIMER UN ÉTABLISSEMENT 

                     $idEtab=$_REQUEST['idEtab'];  

                     $lgEtab=obtenirDetailEtablissement($connexion, $idEtab);
                     $nomEtab=$lgEtab['nomEtab'];

                     // Cas 1ère étape (on vient de listeEtablissements.php)

                     if ($_REQUEST['action']=='demanderSupprEtab')    
                     {
                        echo "
                        <br><center><h5>Souhaitez-vous vraiment supprimer l'établissement $nomEtab ? 
                        <br><br>
                        <div class='text-center'>
                           <a class='btn btn-outline-info' href='suppressionEtablissement.php?action=validerSupprEtab&amp;idEtab=$idEtab' role='button'>Oui</a>
                           <a class='btn btn-outline-dark' href='listeEtablissements.php' role='button'>Non</a>
                        </div>";
                     }

                     // Cas 2ème étape (on vient de suppressionEtablissement.php)

                     else
                     {
                        supprimerEtablissement($connexion, $idEtab);
                        echo "
                        <br><center><h5>L'établissement $nomEtab a été supprimé</h5>
                        <a class='btn btn-outline-dark' href='listeEtablissements.php' role='button'>Retour</a>";
                     }
                  ?>
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
