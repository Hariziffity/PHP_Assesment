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

    <?php
        function getPages($items, $limit){
            return ceil($items / $limit);
        }
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
                        echo "<td><input name=number type=number value=0 min=0 max= 1000></td>";
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
                        echo "<td><input name=number type=number value=0 min=0 max= 1000></td>";
                        echo "</tr>";
                    endforeach;

                }
            ?>

        </table>
    </div>
    
    </body>
</html>

