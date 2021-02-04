<?php
  $id = $_POST['id'];
  $citsmart = $_POST['citsmart']; 


  #$parametrosJSON = json_encode($_POST);
  #echo $parametrosJSON."\n";


  include 'xml.php';
  if ($citsmart == null) {$citsmart="nulo";}
  switch ($citsmart) {
    case ( $citsmart == 'ati' || $citsmart == 'ATI' ) :
      $post = [
          'domain' => 'https://www.csati.pe.gov.br',
          'id' => "$id",
      ];
      
      $ch = curl_init('http://127.0.0.1/chamados/citsmart/citsmart_executor.php');
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
      
      // execute!
      $response = curl_exec($ch);
      
      // close the connection, release resources used
      curl_close($ch);
      
      // do anything you want with your response
      var_dump($response);

      break;
    case ( $citsmart == 'mpma' || $citsmart == 'MPMA' ) :
      break;
    default:
      echo "Ação inválida";
      break;
  }
?>
