<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>déplacer des données data json en lat & long hémisphère nord/sud</title>
</head>
<body>

<!-- pre c'est plus simple pour la mise en page -->
<pre style="white-space: break-spaces;">

  <form method="post" name="envoiForm">
    <input type="submit" name="envoi">
  </form>

<?php
// Reset/Reload
echo '<h3><a href="'.$_SERVER['REQUEST_URI'].'">Reset/Reload</a></h3><hr>';

// endroit où générer le fichier, www-data doit avoir les droits sur ce dossier
$dossier = "ecritures";

//  ------------ Mettre à jour ce fichier ------------
$dataName = "976";
$extension = ".json";

$name = $dataName.$extension;
$lines = file("$name");
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

  Les fichiers json peuvent être testés ici :
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
// if(!empty($_POST['envoi']) && !empty($_POST['choix']) ) {

  // if ($_POST['choix'] == 'sud') {
  //   $preg = array("/[-+]?([0-9]*\.[0-9]*),([-+]?([0-9]*\.[0-9]*))/");
  // }else {

  $preg = array("/([-+]?([0-9]*\.[0-9]*)),([-+]?([0-9]*\.[0-9]*))/");

  // }

  $newData = preg_replace_callback($preg, "numCallBack", $lines);
  // print_r($newData);

  $fp = fopen($dossier.'/'.$creaName, 'w');

  // echo '<h3><a href="'.$_SERVER['REQUEST_URI'].'">Reset</a></h3><hr>';

  foreach ($newData as $key => $value) {
    // on écrit les données
    fwrite($fp, $value);
    // et puis comme ça on voit si ça semble correspondre
    echo "$fp, $value \n";
  }
  // on ferme et écrit le fichier
  fclose($fp);

} else {

  echo "Éditer ce fichier pour son fonctionnement avant de valider,
  le fichier json est : <b>$name</b>
  Il sera généré en tant que <b>$creaName</b>
  dans le dossier : <b>$dossier.</b>";

}

?>
</pre>


</body>
</html>