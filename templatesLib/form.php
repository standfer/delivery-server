<h3>Редактирование заказа</h3>
<form method="POST">
    <table>
        <tr>
            <td>Адрес заказа:</td>
            <td><input type="text" name="address" value="<?= e($row['address']) ?>"></td>
        </tr>
        <tr>
            <td>Телефон:</td>
            <td><input type="text" name="phoneNumber" value="<?= e($row['phoneNumber']) ?>"></td>
        </tr>
        <tr>
            <td>Стоимость заказа:</td>
            <td><input type="text" name="cost" value="<?= e($row['cost']) ?>"></td>
        </tr>
        <tr>
            <td>Статус заказа:</td>
            <td><input type="text" name="isDelivered" value="<?= e($row['isDelivered']) ?>"></td>
        </tr>
        <tr>
            <td>Курьер подтвердил:</td>
            <td>
                <select name='isAssigned'>
                    <option value='1' <?php if ($row['isAssigned'] == 1): ?> selected="selected"<?php endif; ?>>Да</option>
                    <option value='0' <?php if ($row['isAssigned'] == 0): ?> selected="selected"<?php endif; ?>>Нет</option>
                </select>
            </td>
        </tr>
    </table>
    
    <input type="hidden" name="id" value="<?= e($row['id']) ?>">
    <input type="submit" value="Сохранить изменения">
    <?php if ($row['id']): ?>
        <input type="hidden" name="delete" value="<?= e($row['id']) ?>">
        <input type="submit" value="Удалить назначение"><br>
    <?php endif ?>
</form>

<a href="?">Вернуться к списку</a>