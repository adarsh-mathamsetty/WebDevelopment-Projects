<?php
    session_start();
    error_reporting(0);
    ?>

<html>
<head>
<title>Fill Basket</title>
<h2> Welcome to Shopping Basket! Have a great Time.</h2>
</head>

<body style="background-color:black;color:white;">

<?php
    
    if (!isset($_SESSION['cartList'])) {
        $_SESSION['cartList'] = array ();
    }
    
    if(!isset($_SESSION['totalcost'])) {
        $_SESSION['totalcost'] = (float)0;
    }
    
    if($_GET['ItemtoBuy']) {
        $ItemsList = $_GET['ItemtoBuy'];
        
        $Details = file_get_contents("http://sandbox.api.ebaycommercenetwork.com/publisher/3.0/rest/GeneralSearch?apiKey=78b0db8a-0ee1-4939-a2f9-d3cd95ec0fcc&visitorUserAgent&visitorIPAddress&trackingId=7000610&productId=".$ItemsList);
        $itemDetails = new SimpleXMLElement($Details);
        
        $prodprice = (float)$itemDetails->categories->category->items->product->minPrice;
        
        if(array_key_exists($_GET['ItemtoBuy'],$_SESSION['cartList'])==false) {
            $Addedintocart = "<b>".(string)$itemDetails->categories->category->items->product->name ."</b><br /> successfully added to the cart";
        } else {
            $AlreadyPresent = (string)$itemDetails->categories->category->items->product->name ."<br/> already present in the cart";
        }
        
        if(array_key_exists($_GET['ItemtoBuy'],$_SESSION['cartList'])==false) {
            $_SESSION['totalcost'] += (float)$prodprice;
        }
        $_SESSION['cartList'][$_GET['ItemtoBuy']]['id'] =(double)$itemDetails->categories->category->items->product['id'];
        $_SESSION['cartList'][$_GET['ItemtoBuy']]['name'] = (string)$itemDetails->categories->category->items->product->name;
        $_SESSION['cartList'][$_GET['ItemtoBuy']]['image'] = (string)$itemDetails->categories->category->items->product->images->image[0]->sourceURL;
        $_SESSION['cartList'][$_GET['ItemtoBuy']]['price'] = (float)$prodprice;
        $_SESSION['cartList'][$_GET['ItemtoBuy']]['url'] = (string)$itemDetails->categories->category->items->product->productOffersURL;
    }
   
if (!isset($_SESSION['history'])) {
        $_SESSION['history'] = array ();
    }

if (!isset($_SESSION['hist'])) {
        $_SESSION['hist'] = 0;
    }




    	
    
    
    if($_GET['submitForm']) {
        if(!isset($find)) {
            $find = urlencode($_GET["searchWord"]);
        }
        
        if(!isset($catId)) {
            $catId = $_GET["dropdownCat"];
        }
        
        if($find == null) {
            $noResponse = "Please give a search text above";
        }
        else {
            $ResultString = file_get_contents("http://sandbox.api.shopping.com/publisher/3.0/rest/GeneralSearch?apiKey=78b0db8a-0ee1-4939-a2f9-d3cd95ec0fcc&visitorUserAgent&visitorIPAddress&trackingId=7000610&categoryId=". $catId . "&keyword=" . $find . "&numItems=20&showProductOffers=false");
            
            $Result = new SimpleXMLElement($ResultString);
            if (count($Result->categories->category->items)!=null) {
                $searchResponse = "Search results: ";
                
            } else {
                $searchResponse = "No search results found";
                
            }
            
            
        }
    }
    
    if($_GET['clearBasket']) {
        if(!empty($_SESSION['cartList'])) {
            unset($_SESSION['cartList']);
            
            $_SESSION['totalcost'] = 0;
            $clearBasketResponse = "<p>Cart Successfully Cleared!".
            "\n\t</p>\n";
        } else {
            $_SESSION['totalcost'] = 0;
            $emptyBasket = "<p>No items in cart".
            "\n\t</p>\n";
        }
    }
    
    
    if($_GET['deleteItem']) {
        $deleteItemfromCart = $_GET['deleteItem'];
        
        if(array_key_exists($_GET['deleteItem'],$_SESSION['cartList'])==true) {
            $_SESSION['totalcost'] -= (float)$_SESSION['cartList'][$deleteItemfromCart]['price'];
            if($_SESSION['totalcost']<0)
            {
                $_SESSION['totalcost'] = 0;
            }
            $Removed = "<b>".$_SESSION['cartList'][$_GET['deleteItem']]['name'] ."</b><br/> deleted from the cart";
        }
        unset($_SESSION['cartList'][$deleteItemfromCart]);
    }
    
    
    
    
    
    ?>

<!——————Main HTML PAGE—————>
<div >
<form action="buy.php" method="GET">

<?php
    
    $categoryFlow = file_get_contents("http://sandbox.api.ebaycommercenetwork.com/publisher/3.0/rest/CategoryTree?apiKey=78b0db8a-0ee1-4939-a2f9-d3cd95ec0fcc&visitorUserAgent&visitorIPAddress&trackingId=7000610&categoryId=72&showAllDescendants=true");
    $Tree = new SimpleXMLElement($categoryFlow);
    ?>

<fieldset>
<div>Search Products Here:</div><br/><br/>
<label>Category:&nbsp;&nbsp;</label>
<select name="dropdownCat" style="width:270px;">


<?php
    foreach($Tree->category as $SelectedValue) {
        echo "\n\t\t\t\t\t";
        echo "<option value='".$SelectedValue['id']."'>".$SelectedValue->name."</option>" ;
        echo "\n\n";
        foreach($SelectedValue->categories->category as $Categ) {
            echo "\t\t\t\t\t";
            echo "<option value='" . $Categ['id'] . "'>".$Categ->name."</option>";
            echo "\n\t\t\t\t";
            echo "<optgroup label='" . $Categ->name . "' value='" . $categoryGroup['id'] . "'>" . "\n";
            foreach ($Categ->categories as $cat) {
                foreach ($cat->children() as $subCat) {
                    echo "\t\t\t\t\t\t";
                    echo "<b><option value='" . $subCat['id'] . "'>" . $subCat->name . "</option></b>\n";
                }
            }
            echo "\t\t\t\t\t" . "</optgroup>" . "\n";
            echo "\n";
        }
    }
    ?>


</select>

<input  name="searchWord" type="text" placeholder="Enter An Item Name"  />
<button name="submitForm" type="submit" value="submitted" ">Items List</button>
</fieldset>
</form>
</div>

<!————————————Displaying Users Items———————————————>
<div>
<div>
<p>Number of items In your Cart: <?= count($_SESSION['cartList']) ?></p>

</div>
<div>
<div>
<table>

<?php
    foreach($_SESSION['cartList'] as $listCart) {?>
<tr>
<td><img src="<?= $listCart['image']?>"/></a></td>
<td><?= $listCart['name']?></td>
<td>$<?= $listCart['price'] ?></td>
<td>
<a style='color:red;' href="buy.php?deleteItem=<?= $listCart['id'] ?>">Delete Item</a>
<input type = "button" onclick = "location.href='<?= $listCart['url'] ?>';" value= "View Details here! Click Back for shopping" />
</td>
</tr>

<?php } "\n"?>

</table>
</div>
</div>
</div>

<div>
<form action="buy.php" method="get">
<button type="submit" name="clearBasket" value="empty">Clear Basket</button>
</form>
<p>Total Cost: $<?= $_SESSION['totalcost'] ?></p>
</div>







<!------------Responses ------------------>
<p align="center">
<?= $clearBasketResponse ?></p>

<p align="center">
<?= $emptyBasket ?></p>

<p align="center"><?=$Removed?></p>
<p align="center"><?= $Addedintocart?></p>
<p align="center"><?= $AlreadyPresent?></p>


<!———Displaying Search Results——————>
<p>
<b><?=$searchResponse?></b>
</p>
<p align="center">
<?=$noResponse?>
</p>
<div>
<div>
<table>
<?php
$k =0;
    foreach ($Result->children() as $categories) {
        
        if($categories->getName() == "categories") {
            foreach ($categories->category->items->product as $item) {
                
                echo "\n";
                $value = "productURL";
                
                $itemName = $item->name;
                $description = $item->fullDescription;
                $price = $item->minPrice;
                $imageSource = $item->images->image[0]->sourceURL;
                $itemId = $item['id'];
		/* storing the history in session variable */
		$_SESSION['history'][$k]['id'] =$searchItemId;
        	$_SESSION['history'][$k]['name'] = $searchedItemName;
       		$_SESSION['history'][$k]['image'] = $searchItemimageSource;
        	$_SESSION['history'][$k]['price'] = $searchedItemprice;
                
		
		
		echo "<tr>";
                echo "<td><a href=buy.php?ItemtoBuy=". $itemId .
                "><img src='" . $imageSource . "' /></a></td>\n";
                echo "<td>" . $itemName . "</td>\n";
                echo "<td><p>$"
                . $price . "</p></td>\n";
                echo "<td><a style='color:#FFF; text-decoration:none'; href=buy.php?ItemtoBuy=". $itemId .">" . $description . "</a></td>\n";
                echo "<td><a style='color:red;' target = '_blank' href=".$item->productOffersURL.">Details</a></td>";
                echo "</tr>\n\n";
                
		$k++;
		$_SESSION['hist'] = $k;
            }
        }
    }

	/* Search History displaying
		 echo "<p> Search history Results </p>";
		 foreach ($_SESSION['history'] as $id=>value)
			{
				echo "\nprice" .$value['price']."product name".$value['name']."<br/></div>\n";
			}
	*/
    ?>
</table>
</div>
</div>



</body>
</html>
