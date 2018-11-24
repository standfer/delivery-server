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
    <?php foreach ($LIST as $row): ?>
        <tr>
            <td><?= e($row['name']) ?></td>
            <td><?= e($row['addressWorkplace']) ?></td>
            <td><?= e($row['addressOrder']) ?></td>
            <td><?= e($row['phoneNumber']) ?></td>
            <td><?= e($row['cost']) ?></td>
            <td><a href="?idOrder=<?= e($row['idOrder']) ?>">Изменить</a></td>
            <td>            
                <form method="POST">
                    <input type="hidden" name="delete" value="<?= e($row['idOrder']) ?>">
                    <input type="submit" value="Удалить"><br>
                </form>
            </td>
        </tr>
    <?php endforeach ?>
</table>