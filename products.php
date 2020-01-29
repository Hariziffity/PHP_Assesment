<?php
    session_start();
?>
<!DOCTYPE html>
<html>
    <body>
    <?php
        include 'header.php';
        include 'form.php';
    ?>

    <?php
        $products_file = 'products.json';
        $products_data = file_get_contents($products_file);
        $products = json_decode($products_data, true);     
    ?>

    <div class="pagination">
        <ul>
            <?php
                $page = !isset($_GET['page']) ? 1 : $_GET['page'];

                if ($_GET['show'] === 'show_specific') {
                    $total_pages = 1;
                } else if ($_GET['show'] === 'show_all') {
                    $limit = 5;
                    $total_items = count($products);
                    $total_pages = ceil($total_items / $limit);
                }

                for($x = 1; $x <= $total_pages ; $x++) {
                    $activeClass = ($x == $page) ? 'active' : '';
                    $showValue = $_GET['show'];
                    echo "<a id=$x href='products.php?search=&categories=all&show=$showValue&page=$x' class=$activeClass>$x</a>";
                }

            ?>
        </ul>
    </div>

    <div class="container">
        <div class="tableDiv">
            <table>
                <tr>
                    <th>
                        PRODUCT
                    </th>
                    <th>
                        SKU
                    </th>
                    <th>
                        PRICE
                    </th>
                    <th>
                        ACTION
                    </th>
                </tr>

                <?php
                    if($_GET['show'] === 'show_specific') {

                        $page = !isset($_GET['page']) ? 1 : $_GET['page'];
                        $category = !isset($_GET['categories']) ? 'all' : $_GET['categories'];
                        $specific_products = array_filter($products, function($var) use ($category) {
                            if ($category === 'all'){
                                return $var;
                            }
                            return ($var['category'] == $category);
                        });

                        $limit = 5;
                        $offset = ($page - 1) * $limit;
                        $total_items = count($specific_products);
                        $total_pages = ceil($total_items / $limit);
                        $final_specific_products = array_splice($specific_products, $offset, $limit);

                        foreach ($final_specific_products as $product) : 
                            echo "<tr>";
                            echo "<td>" . $product['name'] . "</td>";
                            echo "<td>" . $product['skuid'] . "</td>";
                            echo "<td>" . $product['price'] . "</td>";
                            echo "<td><form id='$product[skuid]form' method='GET'><input type=hidden name='search'/><input type=hidden name='categories' value='$_GET[categories]' /><input type=hidden name='show' value='$_GET[show]'/></form></td>";
                            echo "<td><input form='$product[skuid]form' name=number type=number value=0 min=0 max=1000 id='$product[skuid]' ></td>";
                            echo "<td><button form='$product[skuid]form' name='add_to_cart' class='btn' type='submit' value='$product[skuid]'>ADD TO CART</button></td>";
                            echo "</tr>";
                        endforeach;

                    } else if ($_GET['show'] === 'show_all') {

                        $limit = 5;
                        $offset = ($page - 1) * $limit;
                        $total_items = count($products);
                        $total_pages = ceil($total_items / $limit);
                        $final_specific_products = array_splice($products, $offset, $limit);

                        foreach ($final_specific_products as $product) : 
                            echo "<tr>";
                            echo "<td>" . $product['name'] . "</td>";
                            echo "<td>" . $product['skuid'] . "</td>";
                            echo "<td>" . $product['price'] . "</td>";
                            echo "<td><form id='$product[skuid]form' method='GET'><input type=hidden name='search'/><input type=hidden name='categories' value='$_GET[categories]' /><input type=hidden name='show' value='$_GET[show]'/></form></td>";
                            echo "<td><input form='$product[skuid]form' name=number type=number value=0 min=0 max=1000 id='$product[skuid]' ></td>";
                            echo "<td><button form='$product[skuid]form' name='add_to_cart' class='btn' type='submit' value='$product[skuid]'>ADD TO CART</button></td>";
                            echo "</tr>";
                        endforeach;
                    }
                ?>

            </table>
        </div>
        <div class="cartDiv">
            <span style="text-align: center;"><p>CART</p></span>
            <?php 

                $cart = array();

                function addToCart($item, $count){
                    $_SESSION['cart'][$item] = $count;
                }

                $add_to_cart_item = !isset($_GET['add_to_cart']) ? '' : $_GET['add_to_cart'];

                if(isset($add_to_cart_item)){
                    $add_to_cart_existing_value = !isset($_SESSION['cart'][$add_to_cart_item]) ? 0 : (int) $_SESSION['cart'][$add_to_cart_item];
                    $item_count_value = !isset($_GET['number']) ? 0 : (int) $_GET['number'];
                    $add_to_cart_new_value = $add_to_cart_existing_value + $item_count_value;

                    addToCart($add_to_cart_item, $add_to_cart_new_value);
                }

                function deleteFromCart($item){
                    $_SESSION['cart'][$item] = 0;
                }

                $delete_from_cart_value = !isset($_GET['delete_from_cart']) ? '' : $_GET['delete_from_cart'];

                if (isset($delete_from_cart_value)) {
                    deleteFromCart($delete_from_cart_value);
                }

                foreach ($_SESSION['cart'] as $key => $value) {
                    foreach ($products as $product) {
                        if ($product['skuid'] == $key && $value !== 0){
                            echo "<div>";
                            echo $product['name'];
                            echo "<form id='$product[skuid]clear' method='GET'><input type=hidden name='search'/><input type=hidden name='categories' value='$_GET[categories]' /><input type=hidden name='show' value='$_GET[show]'/></form>" ;
                            echo "<button form='$product[skuid]clear' name='delete_from_cart' class='btn' type='submit' value='$product[skuid]' style='float:right;'>X</button>";
                            echo "</div>";
                            echo "<div style='clear:both;'></div>";
                            echo $value . " * " .  $product['price'];
                            echo "<hr>";
                        }
                    }
                }       

            ?>
        </div>
        <div style="clear:both;"></div>
    </div>
    
    </body>
</html>

