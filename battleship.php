<?php
function validate_battlefield(array $field): bool {
  // Write your magic here ;)
  
  // ships counters
  $ships = array(1 => 4, 2 => 3, 3 => 2, 4 => 1);
  
  for ($y=0;$y<10;$y++) {
    for ($x=0;$x<10;$x++) {
     
      // if ship found     
      if (($field[$y][$x]==1)) {
        
        // check diagonal for ships
        if ($x>0) {
          if (($field[$y+1][$x+1]==1) || ($field[$y+1][$x-1]==1)) {
            return false;
          }
        }        
        
        // check neighbors together
        if (($field[$y+1][$x]==1) && ($field[$y][$x+1]==1)) {
          return false;
        }         
        
        //check for (1) ship
        if (($field[$y+1][$x]!=1) && ($field[$y][$x+1]!=1)) { 
          //decrement ships
          $ships[1]--;
          //remove ship
          $field[$y][$x] = 7;          
        }
        
        // chec for bigger ships
        $ship_len = 1;

        //check horizontal ship
        if (($field[$y+1][$x]!=1) && ($field[$y][$x+1]==1)) {         
          while ($field[$y][$x+$ship_len]==1) {
            //check neighbors
            if (($field[$y+1][$x+$ship_len-1]!=1) && ($field[$y+1][$x+$ship_len]!=1) && ($field[$y+1][$x+$ship_len+1]!=1)) {
              $ship_len++;           
            } else {
              return false;
            }
          }
          if (($ship_len>0) && ($ship_len<5)) {
            //decrement ships
            $ships[$ship_len]--;
            //remove ship
            for ($i=0;$i<$ship_len;$i++) {
              $field[$y][$x+$i] = $ship_len;
            }      
          }
        }
        
        //check vertical ship
        if (($field[$y+1][$x]==1) && ($field[$y][$x+1]!=1)) {    
          while ($field[$y+$ship_len][$x]==1) {
            //check neighbors
            if (($field[$y+$ship_len-1][$x+1]!=1) && ($field[$y+$ship_len][$x+1]!=1) && ($field[$y+$ship_len+1][$x+1]!=1)) {
              $ship_len++;           
            } else {
              return false;
            }
          }
          if (($ship_len>0) && ($ship_len<5)) {
            //decrement ships
            $ships[$ship_len]--;
            //remove ship
            for ($i=0;$i<$ship_len;$i++) {
              $field[$y+$i][$x] = $ship_len;
            }
          }            
        }             
        
  
      }
      echo ($field[$y][$x]); 
      
    }
    echo ("<br>");
  }
  
  // show stats
  for ($i=1;$i<5;$i++) {
    echo "ship ".$i." -- ".$ships[$i]."<br>";
    if ($ships[$i] != 0) { return false; }
  }
  
  return true;
}

?>