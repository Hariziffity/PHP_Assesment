<!DOCTYPE html>
<html>
    <form action="products.php" method="get">
        <input name="search" type="text" placeholder="Search...">
        <select name="categories">
            <option value="all">All Categories</option>
            <option value="electronics">Electronics</option>
            <option value="clothes">Clothes</option>
            <option value="appliances">Appliances</option>
            <option value="accessories">Accessories</option>
        </select>
        <button name="show" class="btn" value="show_specific">SEARCH</button>
        <button name="show" class="btn" value="show_all">SHOW ALL</button> 
    </form>
</html>