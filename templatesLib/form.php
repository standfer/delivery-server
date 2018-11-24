<form method="POST">
    Адрес заказа: <input type="text" name="address" value="<?= e($row['address']) ?>"><br>
    Телефон: <input type="text" name="phoneNumber" value="<?= e($row['phoneNumber']) ?>"><br>
    Стоимость заказа: <input type="text" name="cost" value="<?= e($row['cost']) ?>"><br>
    Статус заказа: <input type="text" name="isDelivered" value="<?= e($row['isDelivered']) ?>"><br>
    <input type="hidden" name="id" value="<?= e($row['id']) ?>">
    <input type="submit"><br>
</form>
<a href="?">Вернуться к списку</a>

<?php if ($row['id']): ?>
<form method="POST">
    <input type="hidden" name="delete" value="<?= e($row['id']) ?>">
    <input type="submit" value="Delete"><br>
</form>
<?php endif ?>