<?php
    include 'delivery\db\loaders.php';
    
?>
<a href="?id=0">Add item</a>
<table border=1>
    <tr>
        <td><b>Курьер</b></td>
        <td><b>Адрес базы</b></td>
        <td><b>Адрес заказа</b></td>
        <td><b>Телефон</b></td>
        <td><b>Стоимость</b></td>
        <td><b>Действие</b></td>
        
    </tr>
<?php 
    $locationsOrders = loadLocationsOrders();
foreach ($locationsOrders as $row) {?>
    <tr>
        <?php
        printf("<td>%s</td>", $row['name']);
        printf("<td>%s</td>", $row['addressWorkplace']);
        printf("<td>%s</td>", $row['addressOrder']);
        printf("<td>%s</td>", $row['phoneNumber']);
        printf("<td>%s</td>", $row['cost']);
        printf("<td><a href='?id=%s'>Изменить |</a>", $row['idCourier']);
        printf("<a href='/delivery/db/crud.php?action=deleteOrderByCourier&orderId=%s' onClick='return confirm(\"Вы уверены?\")'>Удалить</a></td>", $row['idOrder']);
        ?>
<!--        <td><?$row["addressWorkplace"]?></td>
        <td><?$row["addressOrder"]?></td>
        <td><?$row["phoneNumber"]?></td>
        <td><?$row["cost"]?></td>
        <td><a href="?id=<?$row['idCourier']?>">Edit</a></td>
        <td>
            <a href="edit.php?id=$res[id]">Изменить</a> | 
            <a href="crud.php?id=$res[id]" onClick="return confirm('Вы уверены?')">Удалить</a>
        </td>
        -->
    </tr>
<?php } ?>
</table>