<!DOCTYPE html>
<html>
   @include ("header")
   <body class="hold-transition sidebar-mini layout-fixed">
      <div class="wrapper">

          @include("_debut") 

         <!-- Content Wrapper. Contains page content -->
         <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <div class="content-header">
               <div class="container-fluid">
               <div class="row mb-2">
                  <div class="col-sm-6">
                     <h1 class="m-0 text-dark">Accueil</h1>
                  </div><!-- /.col -->
               </div><!-- /.row -->
               </div><!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->

            <!-- Main content -->
            <section class="content">
               <div class="container-fluid">
                  <table width='80%' cellspacing='0' cellpadding='0' align='center'>
                     <tr>  
                        <td class='texteAccueil'>
                        Cette application web permet de gérer l’hébergement des équipes participant au festival Sp’Or.
                        </td>
                     </tr>
                     <tr>
                        <td>&nbsp;
                        </td>
                     </tr>
                     <tr>
                        <td class='texteAccueil'>
                           Elle offre les services suivants :
                        </td>
                     </tr>
                     <tr>
                        <td>&nbsp;
                        </td>
                     </tr>
                     <tr>
                        <td class='texteAccueil'>
                        <ul>
                           <li>Gérer les établissements (caractéristiques et capacités d'accueil) acceptant d'héberger les équipes.
                           <p>
                           </p>
                           <li>Consulter, réaliser ou modifier les attributions des chambres aux équipes dans les établissements.
                        </ul>
                        </td>
                     </tr>
                  </table>
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