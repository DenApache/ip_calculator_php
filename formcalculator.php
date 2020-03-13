<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="style.css" />
</head>

<body>
    <h1>Ip Calculator</h1>
    <form action="formcalculator.php" method="POST">
        <table class="tableform">
            <tr>
                <td class = "header"> <b>Address</b>(Host or Network)</td>
                <td class = "header"> <b>Netmask </b></td>
            <tr>
                <td class = "header"> <input type="text" name="address" /> / </td>
                <td class = "header"> <input type="text" name="mask" />  </td>
            </tr>
            <tr>
                <td> <input type="submit" value="Calculate"/> </td>
            </tr>
        </table>
    </form>

    <?php
        include 'ip_function.php';

        if($_SERVER["REQUEST_METHOD"] == "POST"){
            $address = $_POST["address"];
            $netmask = $_POST["mask"];
                
            if (checkAddress($address) and (checkAddress($netmask) or checkMaskDec($netmask))){
                if(empty($netmask)){
                    $netmask = noMask($address, $netaddress);
                    $decmask = convertAddrMask($netmask);
                }
                else if(!strstr($netmask, ".")){
                    $decmask = $netmask;
                    $netmask = convertMaskAddr($netmask);
                    $netaddress = getNetAddr($address, $netmask);
                } else {
                    $netaddress = getNetAddr($address, $netmask);
                    $decmask = convertAddrMask($netmask);
                }

            } else {
                $address = $netmask = $netaddress="0.0.0.0";
                $decmask = 32;
                echo "<div class = \"error\"> <p> Error with input's parameters !";
                echo " <br> Please check your fields.</p></div>";
            }
        }

                $wildcart = getWildcart($netmask);
                $broadcast = getBroadcast($netaddress, $wildcart);
                $hostmin = getHostMin($netaddress);
                $hostmax = getHostMax($broadcast);
    ?>
    
    <table class="tableResult">
        <tr> 
            <td> Address : </td> 
            <td class = "ipformat"> <?=$address ?> </td> 
            <td> <?=convertDecBin($address) ?> </td> 
        </tr>
        <tr> 
            <td> Netmask : </td> 
            <td class = "ipformat"> <?=$netmask?>  = <?=$decmask?></td> 
            <td class= "netmask"> <?=convertDecBin($netmask)?> </td> 
        </tr>
        <tr> 
            <td> Wildcart : </td> 
            <td class = "ipformat"> <?=$wildcart ?> </td> 
            <td> <?=convertDecBin($wildcart)?> </td>
        </tr>

        <tr class = "separation"> <td> => </td> </tr>

        <tr> 
            <td> Network : </td> 
            <td class = "ipformat"> <?=$netaddress?> / <?=$decmask ?></td> 
            <td> <?=convertDecBin($netaddress)?> </td> 
        </tr>
        <tr> 
            <td> Broadcast : </td> 
            <td class = "ipformat"> <?=$broadcast ?>  </td> 
            <td> <?=convertDecBin($broadcast)?> </td> 
            <td class = "class">(<?=getClass($address)?>)</td>
         </tr>
        <tr> 
            <td> HostMin : </td> 
            <td class ="ipformat"> <?=$hostmin ?> </td> 
            <td> <?=convertDecBin($hostmin) ?></td> 
        </tr>
        <tr> 
            <td> HostMax : </td> 
            <td class ="ipformat"> <?=$hostmax ?> </td> 
            <td> <?=convertDecBin($hostmax)?> </td></tr>
        <tr> 
            <td> Host/Net :</td> 
            <td class ="ipformat"> <?=getNbHost($decmask)?></td>
        </tr>

    </table>
</body>

</html>