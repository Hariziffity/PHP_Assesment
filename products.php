<!DOCTYPE html>
<html>
    <head>
        <script>
            function onLinkClick(x) {
                // var xhttp = new XMLHttpRequest();
                // xhttp.onreadystatechange = function() {
                //     if(this.readyState == 4 && this.status == 200) {
                //         console.log('done');
                //     }
                // };

                // xhttp.open("GET", "products.php?page=" + x, true);
                // xhttp.send();

                document.getElementById(x).className = 'active';
                // event.preventDefault();
            }
        </script>
    </head>
    <body>
    <?php
        include 'header.php';
        include 'form.php';
    ?>

    <?php
        $products_file = 'products.json';
        $products_data = file_get_contents($products_file);
        $products = json_decode($products_data);     
    ?>

    <div class="pagination">
        <ul>

            <?php
            
                $page = !isset($_GET['page']) ? 1 : $_GET['page'];

                $limit = 5;
                $total_items = count($products);
                $total_pages = ceil($total_items / $limit);

                for($x = 1; $x <= $total_pages ; $x++) {
                    echo "<a id=$x href='products.php?search=&categories=all&show=show_specific&page=$x' onclick='onLinkClick($x)'>$x</a>";
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

                    $limit = 5;
                    $offset = ($page - 1) * $limit;
                    $total_items = count($products);
                    $total_pages = ceil($total_items / $limit);
                    $specific_products = array_splice($products, $offset, $limit);

                    foreach ($specific_products as $product) : 
                        echo "<tr>";
                        echo "<td>" . $product->name . "</td>";
                        echo "<td>" . $product->skuid . "</td>";
                        echo "<td>" . $product->price . "</td>";
                        echo "<td><input name=number type=number value=0 min=0 max= 1000></td>";
                        echo "</tr>";
                    endforeach;

                } else if ($_GET['show'] === 'show_all') {

                    foreach ($products as $product) : 
                        echo "<tr>";
                        echo "<td>" . $product->name . "</td>";
                        echo "<td>" . $product->skuid . "</td>";
                        echo "<td>" . $product->price . "</td>";
                        echo "<td><input name=number type=number value=0 min=0 max= 1000></td>";
                        echo "</tr>";
                    endforeach;

                }
            ?>

        </table>
    </div>
    
    </body>
</html>

