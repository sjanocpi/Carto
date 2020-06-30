<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>déplacer des données data json en lat & long hémisphère nord/sud</title>
</head>
<body>

<!--  Ce document sert à déplacer les coordonnées des territoires outre-marins à coté de la métropole afin d'avoir une carte centrale.
      Il va récupérer un fichier au format json et en faire une copie avec les nouvelles valeurs.
      Ce fichier Json pourrra être utilisé comme source de données dans une carte.
      Les valeurs sont à entrer à la mano, tout comme le nom du json/geojson original.
      C'est dans la fonction numCallBack que les valeurs se changent, en fonction de l'hémisphère les valeus seront plus ou moins importantes,
      négatives ou positives.
      Voir commentaires plus bas dans le code.
-->

<!-- pre c'est plus simple pour la mise en page -->
<pre style="white-space: break-spaces;">

  <form method="post" name="envoiForm">
    <input type="submit" name="envoi">
  </form>

<?php
// -- Fichier à placer en locahost

// Reset/Reload
echo '<h3><a href="'.$_SERVER['REQUEST_URI'].'">Reset/Reload</a></h3><hr>';

// Dossier où générer le fichier, www-data doit avoir les droits sur ce dossier
$dossier = "ecritures";

//  ------------ Mettre à jour ce fichier ------------
// format data du type geojson, pas d 'espaces entre les accolades ou les virgules :
// "geometry":{"type":"Polygon","coordinates":[[[-61.163143,14.815349],[-61.162844,14.815126] ...

$dataName = "976";
//  json geosjson etc.
$extension = ".json";
// On concatène les 2 pour avoir le nom complet, par ex. 976.json
$name = $dataName.$extension;
//
$dataLines = file("$name");
// pour écrire dans celui-ci
$creaName = $dataName."-move".$extension;


/*  ------------ Pour le calcul dans la fonction numCallBack ------------
  par exemple :
  Un point dans le Golfe de Gascogne renvoie :
  Latitude	45.6140
  Longitude	-4.7241

  La Réunion renvoie
  [55.321167,-21.248531]

  les valeurs
  $long = $matches[1] - 60.5;
  et
  $lat = $matches[3] + 66.8;
  placeront La Réunion à peu près à coté de ce point

  Mayotte :
  $long = $matches[1] - 50.6;
  $lat = $matches[3] + 59.0;
  pour à-peu-près pareil


  La Martinique :
  $long = $matches[1] + 56.6;
  $lat = $matches[3] + 31.4;
  pour à-peu-près pareil


  Le placement des données du fichiers json peut être testé ici :
  http://geojson.io/#map=2/20.0/0.0

  le regex ici
  https://regex101.com/
*/

function numCallBack($matches) {
  // longitudes
  $long = $matches[1] - 50.6;
  // latitudes
  $lat = $matches[3] + 59.0;
  $r = $long.','.$lat;
  return $r;
}

if(!empty($_POST['envoi'])) {

  $preg = array("/([-+]?([0-9]*\.[0-9]*)),([-+]?([0-9]*\.[0-9]*))/");


  $newData = preg_replace_callback($preg, "numCallBack", $dataLines);

  $fp = fopen($dossier.'/'.$creaName, 'w');

  // on loop et
  foreach ($newData as $key => $value) {
    // on écrit les données
    fwrite($fp, $value);
    // et puis comme ça on voit si ça semble correspondre
    echo "$fp, $value \n";
  }
  // on ferme et écrit le fichier
  fclose($fp);

} else {
  // mode par défaut
  echo "Éditer ce fichier pour son fonctionnement avant de valider,
  le fichier json est : <b>$name</b>
  Il sera généré en tant que <b>$creaName</b>
  dans le dossier : <b>$dossier.</b>";

}

?>
</pre>


</body>
</html>
