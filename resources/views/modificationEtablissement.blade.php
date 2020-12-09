<!DOCTYPE html>
<html>
   @include("header");
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
                     <h1 class="m-0 text-dark">Modification établissement</h1>
                  </div><!-- /.col -->
                  <div class="col-sm-6">
                     <ol class="breadcrumb float-sm-right">
                     <li class="breadcrumb-item"><a href="index.php">Accueil</a></li>
                     <li class="breadcrumb-item"><a href="listeEtablissements.php">Liste établissement</a></li>
                     <li class="breadcrumb-item active">Modification établissement</li>
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

                     // MODIFIER UN ÉTABLISSEMENT 

                     // Déclaration du tableau des civilités
                     $tabCivilite=array("M.","Mme","Melle");  

                     $action=$_REQUEST['action'];
                     $method = $_SERVER['REQUEST_METHOD'];
                     if ($method == 'POST') {
                        $idEtab=$_POST['idEtab'];
                     } else {
                        $idEtab=$_GET['idEtab'];
                     }

                     // Si on ne "vient" pas de ce formulaire, il faut récupérer les données à partir 
                     // de la base (en appelant la fonction obtenirDetailEtablissement) sinon on 
                     // affiche les valeurs précédemment contenues dans le formulaire
                     if ($action=='demanderModifEtab')
                     {
                        $lgEtab=obtenirDetailEtablissement($connexion, $idEtab);
                        $nomEtab=$lgEtab['nomEtab'];
                        $adresseRue=$lgEtab['adresseRue'];
                        $codePostal=$lgEtab['codePostal'];
                        $ville=$lgEtab['ville'];
                        $tel=$lgEtab['tel'];
                        $adresseElectronique=$lgEtab['adresseElectronique'];
                        $type=$lgEtab['type'];
                        $civiliteResponsable=$lgEtab['civiliteResponsable'];
                        $nomResponsable=$lgEtab['nomResponsable'];
                        $prenomResponsable=$lgEtab['prenomResponsable'];
                        $nombreChambresOffertes=$lgEtab['nombreChambresOffertes'];
                     }
                     else
                     {
                        $nomEtab=$_REQUEST['nomEtab']; 
                        $adresseRue=$_REQUEST['adresseRue'];
                        $codePostal=$_REQUEST['codePostal'];
                        $ville=$_REQUEST['ville'];
                        $tel=$_REQUEST['tel'];
                        $adresseElectronique=$_REQUEST['adresseElectronique'];
                        $type=$_REQUEST['type'];
                        $civiliteResponsable=$_REQUEST['civiliteResponsable'];
                        $nomResponsable=$_REQUEST['nomResponsable'];
                        $prenomResponsable=$_REQUEST['prenomResponsable'];
                        $nombreChambresOffertes=$_REQUEST['nombreChambresOffertes'];

                        verifierDonneesEtabM($connexion, $idEtab, $nomEtab, $adresseRue, $codePostal, $ville,  
                                             $tel, $nomResponsable, $nombreChambresOffertes);      
                        if (nbErreurs()==0)
                        {        
                           modifierEtablissement($connexion, $idEtab, $nomEtab, $adresseRue, $codePostal, $ville, 
                                                $tel, $adresseElectronique, $type, $civiliteResponsable, 
                                                $nomResponsable, $prenomResponsable, $nombreChambresOffertes);
                        }
                     }

                     echo '<div class="card card-info">
                              <div class="card-header">
                                 <h3 class="card-title">'.$nomEtab.' ('.$idEtab.')</h3>
                              </div>
                              <form class="formModifEtab method="POST" action="modificationEtablissement.php">
                                 <input type="hidden" value="validerModifEtab" name="action">
                                       <div class="card-body">
                                             <div class="row">
                                                   <!-- START OF FIRST COL-->
                                                   <div class="col">
                                                      <h5>Etablissement</h5>
                                                      <div class="form-group">
                                                         <label for="formEtab">Id*:</label>
                                                         <input type="text" value="'.$idEtab.'" name="idEtab" class="form-control" readonly>
                                                      </div>
                                                      <div class="form-group">
                                                         <label for="formEtab">Nom*:</label>
                                                         <input type="text" value="'.$nomEtab.'" name="nomEtab" class="form-control">
                                                      </div>
                                                      <div class="form-group">
                                                         <label for="formEtab">Adresse*:</label>
                                                         <input type="text" value="'.$adresseRue.'" name="adresseRue" class="form-control">
                                                      </div>
                                                      <div class="form-group">
                                                         <label for="formEtab">Code postal*:</label>
                                                         <input input type="text" value="'.$codePostal.'" name="codePostal" class="form-control">
                                                      </div>
                                                      <div class="form-group">
                                                         <label for="formEtab">Ville*:</label>
                                                         <input type="text" value="'.$ville.'" name="ville" class="form-control">
                                                      </div>
                                                      <div class="form-group">
                                                         <label for="formEtab">Téléphone*:</label>
                                                         <input type="text" value="'.$tel.'" name="tel" class="form-control">
                                                      </div>
                                                      <div class="form-group">
                                                         <label for="formEtab">E-mail:</label>
                                                         <input type="text" value="'.$adresseElectronique.'" name="adresseElectronique" class="form-control">
                                                      </div>
                                                      <div class="form-group">
                                                         <label for="formEtab">Type*:</label>';
                                                         if ($type==1)
                                                         {
                                                            echo '<div class="form-check">
                                                               <input class="form-check-input" type="radio" name="type" id="type" value="1" checked>
                                                               <label class="form-check-label" for="typeRadio">
                                                                  Etablissement Scolaire
                                                               </label>
                                                               </div>
                                                               <div class="form-check">
                                                               <input class="form-check-input" type="radio" name="type" id="type" value="0">
                                                               <label class="form-check-label" for="typeRadio">
                                                                  Autre
                                                               </label>
                                                               </div>
                                                               </div>';
                                                         }
                                                         else
                                                         {
                                                            echo '<div class="form-check">
                                                               <input class="form-check-input" type="radio" name="type" id="type" value="1">
                                                               <label class="form-check-label" for="typeRadio">
                                                                  Etablissement Scolaire
                                                               </label>
                                                               </div>
                                                               <div class="form-check">
                                                               <input class="form-check-input" type="radio" name="type" id="type" value="0" checked>
                                                               <label class="form-check-label" for="typeRadio">
                                                                  Autre
                                                               </label>
                                                               </div>
                                                               </div>';
                                                         } 
                                                      
                                                   echo '</div>
                                                   <!-- END OF FIRST COL -->
                     
                                                   <!-- START OF SECOND COL -->
                                                   <div class="col">
                                                      <h5>Responsable</h5>
                                                         <div class="form-group">
                                                            <label for="formEtab">Civilité*:</label>
                                                            <select class="form-control" name="civiliteResponsable">';
                                                            for ($i=0; $i<3; $i=$i+1)
                                                               if ($tabCivilite[$i]==$civiliteResponsable) 
                                                               {
                                                                  echo "<option selected>$tabCivilite[$i]</option>";
                                                               }
                                                               else
                                                               {
                                                                  echo "<option>$tabCivilite[$i]</option>";
                                                               }
                                                            echo '</select>
                                                         </div>
                                                      <div class="form-group">
                                                         <label for="formEtab">Nom*:</label>
                                                         <input type="text" value="'.$nomResponsable.'" name="nomResponsable" class="form-control">
                                                      </div>
                                                      <div class="form-group">
                                                         <label for="formEtab">Prénom*:</label>
                                                         <input type="text"  value="'.$prenomResponsable.'" name="prenomResponsable" class="form-control">
                                                      </div>
                                                      <div class="form-group">
                                                         <label for="formEtab">Nombre chambres offertes*:</label>
                                                         <input type="text" value="'.$nombreChambresOffertes.'" name="nombreChambresOffertes" class="form-control">
                                                      </div>
                                                   </div>
                                                   <!-- END OF SECOND COL -->
                                             </div> 
                                       </div>
                                       <div class="text-center">
                                          <input class="btn btn-outline-info" type="submit" value="Valider" name="valider">
                                          <input class="btn btn-outline-dark" type="reset" value="Annuler" name="annuler">
                                       </div>
                                    </form>
                           </div>';

                     // En cas de validation du formulaire : affichage des erreurs ou du message de 
                     // confirmation
                     if ($action=='validerModifEtab')
                     {
                        if (nbErreurs()!=0)
                        {
                           afficherErreurs();
                        }
                        else
                        {
                           echo "<div class='alert alert-success' role='alert'>
                           La modification de l'établissement a été effectuée</div>";
                        }
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
