<?php
  $host=$_SERVER['HTTP_HOST'];
  $domain = $_POST['domain'];
  $id = $_POST['id'];

   include 'xml.php';

   $parametro="solicitacaoservico";
   $url="$domain/citsmart/services/data/$parametro/$id";

   $context = stream_context_create(array('ssl'=>array(
    'verify_peer' => false, 
        "verify_peer_name"=>false
	    )));

   libxml_set_streams_context($context);

   $xmlNode = simplexml_load_file("$url");
   $arrayData = xmlToArray($xmlNode);

   var_dump($arrayData);

   //Procurando Situação
   $situacao=$arrayData['tables']['table']['record']['field'][74]['$'];
   switch ($situacao) {
     case 1:
       $situacao2 = "Em andamento";
       break;
     case 2:
       $situacao2 = "Suspenso";
       break;
     case 3:
       $situacao2 = "Cancelado";
       break;
     case 4:
       $situacao2 = "Resolvido";
       break;
     case 5:
       $situacao2 = "Reaberto";
       break;
     case 6:
       $situacao2 = "Fechado";
       break;
     default:
       $situacao2 = "Reclassificado";
       break;
   }
   
   #var_dump($situacao2);
    
   //Procurando grupo solucionador
   $parametro="grupo";
   $id=$arrayData['tables']['table']['record']['field'][3]['$'];
   $url="$domain/citsmart/services/data/$parametro/$id";

   $xmlNode2 = simplexml_load_file("$url");
   $arrayData2 = xmlToArray($xmlNode2);

   //Procurando tipo de serviço
   $parametro="tipodemandaservico";
   $id=$arrayData['tables']['table']['record']['field'][24]['$'];
   $url="$domain/citsmart/services/data/$parametro/$id";

   $xmlNode3 = simplexml_load_file("$url");
   $arrayData3 = xmlToArray($xmlNode3);
   //Procurando responsavel
   $parametro=usuario;
   $id=$arrayData['tables']['table']['record']['field'][70]['$'];
   $url="$domain/citsmart/services/data/$parametro/$id";

   $xmlNode4 = simplexml_load_file("$url");
   $arrayData4 = xmlToArray($xmlNode4);

   //Procurando solicitante
   $parametro=usuario;
   $id=$arrayData['tables']['table']['record']['field'][7]['$'];
   $url="$domain/citsmart/services/data/$parametro/$id";

   $xmlNode5 = simplexml_load_file("$url");
   $arrayData5 = xmlToArray($xmlNode5);

   //Organizando Array
   $chamado[Id]=$arrayData['tables']['table']['record']['field'][0]['$'];
   $chamado[Tipo]=$arrayData3['tables']['table']['record']['field'][1]['$'];
   $chamado[Grupo]=$arrayData2['tables']['table']['record']['field'][2]['$'];
   $chamado[Descricao]=$arrayData['tables']['table']['record']['field'][58]['$'];
   $chamado[Responsavel]=$arrayData4['tables']['table']['record']['field'][5]['$'];
   $chamado[Solicitante]=$arrayData5['tables']['table']['record']['field'][5]['$'];
   $chamado[Inicio]=$arrayData['tables']['table']['record']['field'][15]['$'];
   $chamado[Captura]=$arrayData['tables']['table']['record']['field'][54]['$'];
   $chamado[Limite]=$arrayData['tables']['table']['record']['field'][10]['$'];
   $chamado[Fim]=$arrayData['tables']['table']['record']['field'][16]['$'];
   $chamado[Situacao]=$situacao2;
   $chamado[Resposta]=$arrayData['tables']['table']['record']['field'][14]['$'];
   $chamado[Causa]=$arrayData['tables']['table']['record']['field'][31]['$'];

   $post = [
       'chamado' => "$chamado[Id]",
       'tipo' => "$chamado[Tipo]",
       'descricao' => "$chamado[Descricao]",
       'solucao' => "$chamado[Resposta]",
       'causa' => "$chamado[Causa]",
   ];
   
   $ch = curl_init('http://homologacao-php.app.tjpe.gov.br/chamados/action_page.php');
   curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
   curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
   
   // execute!
   $response = curl_exec($ch);
   
   // close the connection, release resources used
   curl_close($ch);
   
   // do anything you want with your response
   #var_dump($response);

?>
