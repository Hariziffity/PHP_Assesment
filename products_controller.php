<?php
    session_start();
?>
<!DOCTYPE html>
<html>
<body>
    <?php
    require 'header.php';
    require 'form.php';
    require_once __DIR__.'/products_model.php';
    $productProcess = new Home\ProductsProcessing();

    $search = $_GET["search"];
    $page = !isset($_GET['page']) ? 1 : $_GET['page'];
    $category = !isset($_GET['categories']) ? 'all' : $_GET['categories'];
    $show = $_GET["show"];
    $result = $productProcess->getFinalProducts($search, $category, $page, $show);
    $final_products = $result['final_products'];
    ?>

    <div class="pagination">
        <ul>
            <?php
            $limit = 5;
            $total_items = $result['count'];
            $total_pages = ceil($total_items / $limit);
            for ($x = 1; $x <= $total_pages; $x++) {
                $activeClass = ($x == $page) ? 'active' : '';
                $showValue = $_GET['show'];
                echo "<a id=$x href='products_controller.php?search=&categories=all&show=$showValue&page=$x' 
                class=$activeClass>$x</a>";
            }
            ?>
        </ul>
    </div>

    <div class="container">
        <div class="tableDiv">
            <table id='productTable'>
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
                if (count($final_products) <= 0) {
                    echo "
                            <script type=\"text/javascript\">
                                document.getElementsByClassName('pagination')[0].style.display='none';
                                var table = document.getElementById('productTable');
                                for(let i=0; i <table.rows[0].cells.length ; i++) {
                                    table.rows[0].cells[i].innerText = ''
                                }
                                document.getElementById('productTable').innerText = 'No Products found';
                            </script>
                        ";
                }

                foreach ($final_products as $product) :
                    $offerprice = empty($product['offerprice']) ?  "" : "<ins>" . $product['offerprice'] . "</ins>";
                    $price = empty($offerprice) ? $product['price'] : "<del>" . $product['price'] . "</del>";
                    echo "<tr>";
                    echo "<td>" . $product['name'] . "</td>";
                    echo "<td>" . $product['skuid'] . "</td>";
                    echo "<td>" . $price . " " .  $offerprice . "</td>";
                    echo "<td style=display:none;>
                    <form id='$product[skuid]form' method='GET'>
                    <input type=hidden name='search'/>
                    <input type=hidden name='categories' value='$_GET[categories]' />
                    <input type=hidden name='show' value='$_GET[show]'/></form></td>";
                    echo "<td><input form='$product[skuid]form' name=number type=number 
                    value=0 min=0 max=1000 id='$product[skuid]' ></td>";
                    echo "<td><button form='$product[skuid]form' name='add_to_cart' class='btn' type='submit' 
                    value='$product[skuid]'>ADD TO CART</button></td>";
                    echo "</tr>";
                endforeach;
                ?>

            </table>
        </div>
        <div class="cartDiv">
            <span style="text-align: center;">
                <p>CART</p>
            </span>
            <?php
            $cart = array();

            function addToCart($item, $count)
            {
                $_SESSION['cart'][$item] = $count;
            }

            $add_to_cart_item = !isset($_GET['add_to_cart']) ? '' : $_GET['add_to_cart'];

            if (isset($add_to_cart_item)) {
                $add_to_cart_existing_value = !isset($_SESSION['cart'][$add_to_cart_item])
                    ? 0 : (int) $_SESSION['cart'][$add_to_cart_item];
                $item_count_value = !isset($_GET['number']) ? 0 : (int) $_GET['number'];
                $add_to_cart_new_value = $add_to_cart_existing_value + $item_count_value;

                addToCart($add_to_cart_item, $add_to_cart_new_value);
            }

            function deleteFromCart($item)
            {
                $_SESSION['cart'][$item] = 0;
            }

            $delete_from_cart_value = !isset($_GET['delete_from_cart']) ? '' : $_GET['delete_from_cart'];

            if (isset($delete_from_cart_value)) {
                deleteFromCart($delete_from_cart_value);
            }

            $subtotal = 0;

            $session_prodcuts = array_map(
                function ($obj2, $obj3) {
                    foreach (PRODUCTS as $product) {
                        if ($product['skuid'] == $obj2 && $obj3 !== 0) {
                            $product['value'] = $obj3;
                            return $product;
                        }
                    }
                },
                array_keys($_SESSION['cart']),
                $_SESSION['cart']
            );

            foreach ($session_prodcuts as $product) {
                if (!empty($product['value'])) {
                    $offerprice = floatval(ltrim($product['offerprice'], '$'));
                    $price = floatval(ltrim($product['price'], '$'));
                    $price = empty($offerprice) ? $price : $offerprice;
                    $value = $product['value'];
                    $item_price = $price * $value;
                    $subtotal += (int) $item_price;
                    echo "<div>";
                    echo $product['name'];
                    echo "<form id='$product[skuid]clear' method='GET'>
                    <input type=hidden name='search'/>
                    <input type=hidden name='categories' value='$_GET[categories]' />
                    <input type=hidden name='show' value='$_GET[show]'/></form>";
                    echo "<button form='$product[skuid]clear' name='delete_from_cart' class='btn' type='submit' 
                    value='$product[skuid]' style='float:right;'>X</button>";
                    echo "</div>";
                    echo "<div style='clear:both;'></div>";
                    echo $value . " * " .  $price;
                    echo "<hr>";
                }
            }

            echo ($subtotal > 0) ? '<b>Subtotal</b> :' . $subtotal : '';
            ?>
        </div>
        <div style="clear:both;"></div>
    </div>
</body>
</html>