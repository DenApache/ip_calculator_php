<?php
/**
 * Check the decimal mask
 */
function checkMaskDec($mask){
    return ($mask >-1) and ($mask < 33);
}

/**
 * Check address syntax and value
 */
function checkAddress($address){
    if(empty($address))
        return false;

    $byte = explode(".",$address);
    if(count($byte) != 4)
        return false;

    foreach( $byte as $by){
        if((intval($by) > 255 or intval($by) < 0) and empty($by))
            return false;
    }
    return true;
}

/**
 * Convert decimal in byte
 */
function getBytes($dec){
    $byte = 0;
    for($i = 7; $dec > 0; $i --){
        $byte += pow(2, $i);
        $dec -= 1/8;
    }
    return $byte;
}

/**
 * Convert byte in decimal
 */
function getDec($byte){
    $exp = 7;
    $res = 0;
    while(($byte -= pow(2, $exp)) >= 0){
        $res ++;
        $exp --;
    }
    return $res;
}

/**
 * Convert a mask to ip format address
 */
function convertMaskAddr($netmask){
    $mask = "";
    $res = $size = 0;

    for($res = $netmask / 8; $res  >= 1; $res --){
        $mask .="255.";
        $size ++;
    }

    $mask .= getBytes($res).".";
    $size ++;

    while($size < 4){
        $mask.= "0.";
        $size ++;
    }
    $mask = substr($mask, 0, -1);
    return $mask;
}

/**
 * Convert a address to decimal mask
 */
function convertAddrMask($address){
    $res = 0;

    $byte = explode(".", $address);
    for($i = 0; $byte[$i] == 255; $i++)
        
    for($i = 0; $i < 4; $i++){
        if ($byte[$i] == 255)
            $res += 8;
        else if ($byte[$i] > 0)
            $res += getDec($byte[$i]);
    }
    return $res;
}

/**
 * Return the netmaddress and netmask corresponding
 * to the class of the address
 */
function noMask($address, &$netaddress){
    $byte = explode(".",$address);

    if($byte[0] < 128){
        $netaddress = "$byte[0].0.0.0";
        return "255.0.0.0";
    } else if ($byte[0] < 192){
        $netaddress = "$byte[0].$byte[1].0.0";
        return "255.255.0.0";
    } else if ($byte[0] < 224){
        $netaddress = "$byte[0].$byte[1].$byte[2].0";
        return "255.255.255.0";
    } else{
        return "";
    }
}

/**
 * Return class of an address
 */
function getClass($address){
    $byte = explode(".", $address);
    if($byte[0] < 128)
        return "Class A";
    else if ($byte[0] < 192)
        return "Class B";
    else if ($byte[0] < 224)
        return "Class C";
    else
        return "";
}

/**
 * Return wildcart of a netmask
 */
function getWildcart($netmask){
    $wild = "";
    $bytes = explode(".", $netmask);
    foreach($bytes as $by)
        $wild .= strval(255^$by).".";
    $wild = substr($wild, 0, -1);
    return $wild;
}       

/**
 * Return netaddress with address and netmask
 */
function getNetAddr($address, $netmask){
    $netaddress = "";
    $addrbyte = explode(".", "$address");
    $maskbyte = explode(".", "$netmask");

    for($i = 0; $i < 4; $i++)
         $netaddress .= strval(intval($addrbyte[$i]) & intval($maskbyte[$i])).".";
    $netaddress = substr($netaddress, 0, -1);
    return $netaddress;
}

/**
 * Return broadcast address of a netaddress
 */
function getBroadcast($netaddress, $wildcart){
    $broadcast = "";
    $netbyte = explode(".", "$netaddress");
    $maskbyte = explode(".", "$wildcart");

    for($i = 0; $i < 4; $i++)
        $broadcast .= strval(intval($netbyte[$i]) | intval($maskbyte[$i])).".";    
    $broadcast = substr($broadcast, 0, -1);
    return $broadcast;
}

/**
 * Return the hostmin's address of a netaddress
 */
function getHostMin($netaddress){
    $hostmin = "";
    $byte = explode(".", $netaddress);
    $byte[3] ++;
    foreach($byte as $by)
        $hostmin .= $by.".";
    $hostmin = substr($hostmin, 0, -1);
    return $hostmin;
}

/**
 * Return the hostmax's address of a netaddress
 */
function getHostMax($broadcast){
    $hostmax = "";
    $byte = explode(".", $broadcast);
    $byte[3] --;
    foreach($byte as $by)
        $hostmax .= $by.".";
    $hostmax = substr($hostmax, 0, -1);
    return $hostmax;
}

/**
 * Return number of host possible in a netaddress
 */
function getNbHost($dec){
    return $nbhost = pow(2, 32 - $dec) - 2;
}

/**
 * Convert an address in ip format to a binary format
 */
function convertDecBin($address){
    $byte = explode(".", $address);
    $addbin = "";
    foreach ($byte as $by)
        $addbin .= str_pad(decbin($by), 8, 0, STR_PAD_LEFT).".";
    $addbin = substr($addbin, 0, -1);
    return $addbin;
}
?>