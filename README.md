# Carto
Move Json data lat/long to replace them,  for France and her outer-sea territories


------------ Pour le calcul dans la fonction numCallBack ------------

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
  
*
