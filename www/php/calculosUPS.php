<?php

//funcion para calcular el numero SHP#:

function shp($trackingg)
{
    $tabla=array('3','4','7','8','9','B','C','D','F','G','H','J','K','L','M','N','P','Q','R','S','T','V','W','X','Y','Z');
    $dato=array();
    $shpnumber="";
    //el numero de tracking tendra el formato 
    //  1Z 123 X56 66 2075 4864
    $tracking= str_replace(" ","",$trackingg);
    
    $dat1= (int)(substr($tracking, 10,7));
  
   //calculamos la posicion 1
   $pos1=(int)($dat1/pow(26,4));
   $dato[0]=$pos1;
   
   $pos2=(int)(($dat1-($pos1*pow(26,4)))/pow(26,3));
   $dato[1]=$pos2;
   
   $pos3=(int)(($dat1-($pos1*pow(26,4))-($pos2*pow(26,3)))/pow(26,2));
   $dato[2]=$pos3;
   
   $pos4=(int)(($dat1-($pos1*pow(26,4))-($pos2*pow(26,3))-($pos3*pow(26,2)))/26);
   $dato[3]=$pos4;
 
   $pos5=(int)(($dat1-($pos1*pow(26,4))-($pos2*pow(26,3))-($pos3*pow(26,2)) - ($pos4*pow(26,1))));
   $dato[4]=$pos5;
   
   //UNA VEZ OBTENIDO LAS POSICIONES BUSCAMOS EL NUMERO CORRESPONDIENTE EN EL ARRAY
   for($j=0;$j<5;$j++)
   {
    for($i=0;$i<count($tabla);$i++)
    {
        if($dato[$j]==$i)
        {
          $shpnumber=$shpnumber.$tabla[$i];
          break;
        }
    }
   }
   
   $shpnumber=(substr($tracking, 2,6)).$shpnumber;
   echo $shpnumber;
}

shp("1Z 123 X56 Y6 4400 0050");