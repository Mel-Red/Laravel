<?php

function debug_to_console($data, $context = 'Debug in Console') {

   // Buffering to solve problems frameworks, like header() in this and not a solid return.
   ob_start();

   $output  = 'console.info(\'' . $context . ':\');';
   $output .= 'console.log(' . json_encode($data) . ');';
   $output  = sprintf('<script>%s</script>', $output);

   echo $output;
}

// FONCTIONS DE CONNEXION

function connect() { 
	$user = 'root'; 
	$pass = '';  
	$dsn = 'mysql:host=localhost;dbname=festival'; 
	try{
		$dbh= new PDO($dsn, $user, $pass); 
		return $dbh;
	} catch (PDOException $e){ 
		print "Erreur ! :" . $e->getMessage() . "<br/>"; 
		die(); 
	}
	return null;
}


function selectBase($connexion)
{
   $query="SET CHARACTER SET utf8";
   $res = $connexion->query($query);
   $ok = $connexion->query("use festival");
   return $ok;
}

// FONCTIONS DE GESTION DES ÉTABLISSEMENTS

function obtenirReqEtablissements()
{
   $req="select idEtab, nomEtab from Etablissement order by idEtab";
   return $req;
}

function obtenirReqEtablissementsOffrantChambres()
{
   $req="select idEtab, nomEtab, nombreChambresOffertes from Etablissement where 
         nombreChambresOffertes!=0 order by idEtab";
   return $req;
}

function obtenirReqEtablissementsAyantChambresAttribuées()
{
   $req="select distinct A.idEtab, E.nomEtab, E.nombreChambresOffertes from Etablissement as E, Attribution as A where E.idEtab = A.idEtab order by A.idEtab";
   return $req;
}

function obtenirDetailEtablissement($connexion, $idEtab)
{
   $req="select * from Etablissement where idEtab='$idEtab'";
   $rsEtab=$connexion->query($req);
   $lgEtab=$rsEtab->fetch();
   return $lgEtab;
}

function supprimerEtablissement($connexion, $idEtab)
{
    $req="delete from Etablissement where idEtab='$idEtab'";
    $connexion->exec($req);
}
 
function modifierEtablissement($connexion, $idEtab, $nomEtab, $adresseRue, $codePostal, 
                                $ville, $tel, $adresseElectronique, $type, 
                                $civiliteResponsable, $nomResponsable, 
                                $prenomResponsable, $nombreChambresOffertes)
{  
   $nomEtab=str_replace("'", "''", $nomEtab);
   $adresseRue=str_replace("'","''", $adresseRue);
   $ville=str_replace("'","''", $ville);
   $adresseElectronique=str_replace("'","''", $adresseElectronique);
   $nomResponsable=str_replace("'","''", $nomResponsable);
   $prenomResponsable=str_replace("'","''", $prenomResponsable);
  
   $req="update Etablissement set nomEtab='$nomEtab',adresseRue='$adresseRue',
         codePostal='$codePostal',ville='$ville',tel='$tel',
         adresseElectronique='$adresseElectronique',type='$type',
         civiliteResponsable='$civiliteResponsable',nomResponsable=
         '$nomResponsable',prenomResponsable='$prenomResponsable',
         nombreChambresOffertes='$nombreChambresOffertes' where idEtab='$idEtab'";
   
   $connexion->exec($req);
}

function creerEtablissement($connexion, $idEtab, $nomEtab, $adresseRue, $codePostal, 
                             $ville, $tel, $adresseElectronique, $type, 
                             $civiliteResponsable, $nomResponsable, 
                             $prenomResponsable, $nombreChambresOffertes)
{ 
   $nomEtab=str_replace("'", "''", $nomEtab);
   $adresseRue=str_replace("'","''", $adresseRue);
   $ville=str_replace("'","''", $ville);
   $adresseElectronique=str_replace("'","''", $adresseElectronique);
   $nomResponsable=str_replace("'","''", $nomResponsable);
   $prenomResponsable=str_replace("'","''", $prenomResponsable);
   
   $req="insert into Etablissement values ('$idEtab', '$nomEtab', '$adresseRue', 
         '$codePostal', '$ville', '$tel', '$adresseElectronique', '$type', 
         '$civiliteResponsable', '$nomResponsable', '$prenomResponsable',
         '$nombreChambresOffertes')";
   
   $connexion->exec($req);
}


function estUnIdEtablissement($connexion, $idEtab)
{
   $req="select * from Etablissement where idEtab='$idEtab'";
   $rsEtab=$connexion->query($req);
   $lgEtab=$rsEtab->fetch();
   return $lgEtab;
}

function estUnNomEtablissement($connexion, $mode, $idEtab, $nomEtab)
{
   $nomEtab=str_replace("'", "''", $nomEtab);
//    // S'il s'agit d'une création, on vérifie juste la non existence du nom sinon
//    // on vérifie la non existence d'un autre établissement (id!='$id') portant 
//    // le même nom
   if ($mode=='C')
   {
      $req="select * from Etablissement where nomEtab='$nomEtab'";
   }
   else
   {
      $req="select * from Etablissement where nomEtab='$nomEtab' and idEtab!='$idEtab'";
   }
   $rsEtab=$connexion->query($req);
   $lgEtab=$rsEtab->fetch();
   return $lgEtab;
}

function obtenirNbEtab($connexion)
{
   $req="select count(*) as nombreEtab from Etablissement";
   $rsEtab=$connexion->query($req);
   $lgEtab=$rsEtab->fetch();
   return $lgEtab["nombreEtab"];
}

function obtenirNbEtabOffrantChambres($connexion)
{
   $req="select count(*) as nombreEtabOffrantChambres from Etablissement where 
         nombreChambresOffertes!=0";
   $rsEtabOffrantChambres=$connexion->query($req);
   $lgEtabOffrantChambres=$rsEtabOffrantChambres->fetch();
   return $lgEtabOffrantChambres["nombreEtabOffrantChambres"];
}

// // Retourne false si le nombre de chambres transmis est inférieur au nombre de 
// // chambres occupées pour l'établissement transmis 
// // Retourne true dans le cas contraire
function estModifOffreCorrecte($connexion, $idEtab, $nombreChambres)
{
   $nbOccup=obtenirNbOccup($connexion, $idEtab);
   return ($nombreChambres>=$nbOccup);
}

// // FONCTIONS RELATIVES AUX EquipeS

function obtenirReqIdNomEquipesAHeberger()
{
   $req="select idEquipe, nomEquipe, nomPays from Equipe where hebergement='O' order by idEquipe";
   return $req;
}

function obtenirNomEquipe($connexion, $idEquipe)
{
   $req="select nomEquipe from Equipe where idEquipe='$idEquipe'";
   $rsEquipe=$connexion->query($req);
   $lgEquipe=$rsEquipe->fetch();
   return $lgEquipe["nomEquipe"];
}

// // FONCTIONS RELATIVES AUX ATTRIBUTIONS

// // Teste la présence d'attributions pour l'établissement transmis    
function existeAttributionsEtab($connexion, $idEtab)
{
   $req="select count(*) From Attribution where idEtab='$idEtab'";
   $rsAttrib=$connexion->query($req);
   $lgAttrib=$rsAttrib->fetchColumn();
   return $lgAttrib > 0;
}

// // Retourne le nombre de chambres occupées pour l'id étab transmis
function obtenirNbOccup($connexion, $idEtab)
{
   $req="select IFNULL(sum(nombreChambres), 0) as totalChambresOccup from
      Attribution where idEtab='$idEtab'";
   $rsOccup=$connexion->query($req);
   $lgOccup=$rsOccup->fetch();
   return $lgOccup["totalChambresOccup"];
}

function obtenirNbOffertes($connexion, $idEtab)
{
   $req="select nombreChambresOffertes from Etablissement where idEtab='$idEtab'";
   $rsOffertes=$connexion->query($req);
   $lgOffertes=$rsOffertes->fetch();
   return $lgOffertes["nombreChambresOffertes"];
}

// // Met à jour (suppression, modification ou ajout) l'attribution correspondant à
// // l'id étab et à l'id Equipe transmis
function modifierAttribChamb($connexion, $idEtab, $idEquipe, $nbChambres)
{
   $req="select count(*) as nombreAttribEquipe from Attribution where idEtab=
         '$idEtab' and idEquipe='$idEquipe'";
   $rsAttrib=$connexion->query($req);
   $lgAttrib=$rsAttrib->fetch();
   if ($nbChambres==0)
      $req="delete from Attribution where idEtab='$idEtab' and idEquipe='$idEquipe'";
   else
   {
      if ($lgAttrib["nombreAttribEquipe"]!=0)
         $req="update Attribution set nombreChambres=$nbChambres where idEtab=
              '$idEtab' and idEquipe='$idEquipe'";
      else
         $req="insert into Attribution values('$idEtab','$idEquipe', $nbChambres)";
}
   $connexion->exec($req);
}

// // Retourne la requête permettant d'obtenir les id et noms des Equipes affectés
// // dans l'établissement transmis
function obtenirReqEquipesEtab($idEtab)
{
   $req="select distinct A.idEquipe, E.nomEquipe, E.nomPays from Equipe as E, Attribution as A where 
      A.idEquipe=E.idEquipe and idEtab='$idEtab'";
   return $req;
}
            
// // Retourne le nombre de chambres occupées par le Equipe transmis pour l'id étab
// // et l'id Equipe transmis
function obtenirNbOccupEquipe($connexion, $idEtab, $idEquipe)
{
   $req="select nombreChambres From Attribution where idEtab='$idEtab'
         and idEquipe='$idEquipe'";
   $rsAttribEquipe=$connexion->query($req);
   if ($lgAttribEquipe=$rsAttribEquipe->fetch())
      return $lgAttribEquipe["nombreChambres"];
   else
      return 0;
}
function obtenirNbChambresReserve($connexion, $idEquipe)
{
   $req="select SUM(nombreChambres) as total_ch_reserve from attribution where idEquipe='$idEquipe'";
   $rsNbChRes=$connexion->query($req);
   $lgNbCheRes=$rsNbChRes->fetch();
   return $lgNbCheRes["total_ch_reserve"];
}


?>