<form method="POST">
    Name: <input type="text" name="name" value="<?= e($row['name']) ?>"><br>
    Car: <input type="text" name="car" value="<?= e($row['car']) ?>"><br>
    Sex: <select name="sex">
        <option<?php if ($row['sex'] == 'male'): ?> selected="selected"<?php endif ?>>male</option>
        <option<?php if ($row['sex'] == 'female'): ?> selected="selected"<?php endif ?>>female</option>
    </select>
    <input type="hidden" name="id" value="<?= e($row['id']) ?>">
    <input type="submit"><br>
</form>
<a href="?">Return to the list</a>

<?php if ($row['id']): ?>
<form method="POST">
    <input type="hidden" name="delete" value="<?= e($row['id']) ?>">
    <input type="submit" value="Delete"><br>
</form>
<?php endif ?>