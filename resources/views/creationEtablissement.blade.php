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
                     <h1 class="m-0 text-dark">Création établissement</h1>
                  </div><!-- /.col -->
                  <div class="col-sm-6">
                     <ol class="breadcrumb float-sm-right">
                     <li class="breadcrumb-item"><a href="index.php">Accueil</a></li>
                     <li class="breadcrumb-item"><a href="listeEtablissements.php">Liste établissement</a></li>
                     <li class="breadcrumb-item active">Création établissement</li>
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

                     // CRÉER UN ÉTABLISSEMENT 

                     // Déclaration du tableau des civilités
                     $tabCivilite=array("M.","Mme","Melle");  

                     $action=$_REQUEST['action'];

                     // S'il s'agit d'une création et qu'on ne "vient" pas de ce formulaire (on 
                     // "vient" de ce formulaire uniquement s'il y avait une erreur), il faut définir 
                     // les champs à vide sinon on affichera les valeurs précédemment saisies
                     if ($action=='demanderCreEtab') 
                     {  
                        $idEtab='';
                        $nomEtab='';
                        $adresseRue='';
                        $ville='';
                        $codePostal='';
                        $tel='';
                        $adresseElectronique='';
                        $type=0;
                        $civiliteResponsable='Monsieur';
                        $nomResponsable='';
                        $prenomResponsable='';
                        $nombreChambresOffertes='';
                     }
                     else
                     {
                        $idEtab=$_REQUEST['idEtab']; 
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

                        verifierDonneesEtabC($connexion, $idEtab, $nomEtab, $adresseRue, $codePostal, $ville, 
                                             $tel, $nomResponsable, $nombreChambresOffertes);      
                        if (nbErreurs()==0)
                        {        
                           creerEtablissement($connexion, $idEtab, $nomEtab, $adresseRue, $codePostal, $ville,  
                                             $tel, $adresseElectronique, $type, $civiliteResponsable, 
                                             $nomResponsable, $prenomResponsable, $nombreChambresOffertes);
                        }
                     }
                     
                     echo '<div class="card card-info">
                              <div class="card-header">
                                 <h3 class="card-title">Nouvel établissement</h3>
                              </div>
                              <form class="formCreEtab" method="POST" action="creationEtablissement.php">
                                    <input type="hidden" value="validerCreEtab" name="action">
                                       <div class="card-body">
                                             <div class="row">
                                                   <!-- START OF FIRST COL-->
                                                   <div class="col">
                                                      <h5>Etablissement</h5>
                                                      <div class="form-group">
                                                         <label for="formEtab">Id*:</label>
                                                         <input type="text" class="form-control" value="'.$idEtab.'" name="idEtab">
                                                      </div>
                                                      <div class="form-group">
                                                         <label for="formEtab">Nom*:</label>
                                                         <input type="text" class="form-control" value="'.$nomEtab.'" name="nomEtab">
                                                      </div>
                                                      <div class="form-group">
                                                         <label for="formEtab">Adresse*:</label>
                                                         <input type="text" class="form-control" value="'.$adresseRue.'" name="adresseRue">
                                                      </div>
                                                      <div class="form-group">
                                                         <label for="formEtab">Code postal*:</label>
                                                         <input type="text" class="form-control" value="'.$codePostal.'" name="codePostal">
                                                      </div>
                                                      <div class="form-group">
                                                         <label for="formEtab">Ville*:</label>
                                                         <input type="text" class="form-control" value="'.$ville.'" name="ville">
                                                      </div>
                                                      <div class="form-group">
                                                         <label for="formEtab">Téléphone*:</label>
                                                         <input type="text" class="form-control" value="'.$tel.'" name="tel">
                                                      </div>
                                                      <div class="form-group">
                                                         <label for="formEtab">E-mail:</label>
                                                         <input type="text" class="form-control" value="'.$adresseElectronique.'" name="adresseElectronique">
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
                                                               <input class="form-check-input" type="radio" name="type" value="1">
                                                               <label class="form-check-label" for="typeRadio">
                                                                  Etablissement Scolaire
                                                               </label>
                                                               </div>
                                                               <div class="form-check">
                                                               <input class="form-check-input" type="radio" name="type" value="0" checked>
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
                                                         <input type="text" class="form-control" value="'.$nomResponsable.'" name="nomResponsable">
                                                      </div>
                                                      <div class="form-group">
                                                         <label for="formEtab">Prénom*:</label>
                                                         <input type="text" class="form-control" value="'.$prenomResponsable.'" name="prenomResponsable">
                                                      </div>
                                                      <div class="form-group">
                                                         <label for="formEtab">Nombre chambres offertes*:</label>
                                                         <input type="text" class="form-control" value="'.$nombreChambresOffertes.'" name="nombreChambresOffertes">
                                                      </div>
                                                   </div>
                                                   <!-- END OF SECOND COL -->
                                             </div> 
                                       </div>
                                       <div class="text-center">
                                          <input class="btn btn-outline-info" type="submit" value="Valider" name="valider" />
                                          <input class="btn btn-outline-dark" type="reset" value="Annuler" name="annuler" />
                                       </div>
                                    </form>
                           </div>';

                     // En cas de validation du formulaire : affichage des erreurs ou du message de 
                     // confirmation
                     if ($action=='validerCreEtab')
                     {
                        if (nbErreurs()!=0)
                        {
                           afficherErreurs();
                        }
                        else
                        {
                           echo "<div class='alert alert-success' role='alert'>
                           La création de l'établissement a été effectuée</div>";
                        }
                     }
                  ?>
               </div>
            </section>
            <!-- /.content -->
         </div>
         <!-- /.content-wrapper -->
                  </br>
         @include("_footer");
      </div>
      <!-- ./wrapper -->

      <!-- ./wrapper -->

      @include("jQuery");
   </body>
</html>
