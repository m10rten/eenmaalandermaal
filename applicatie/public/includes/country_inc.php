
<?php


$query = $dbh->prepare("SELECT *  FROM Land WHERE einddatum IS NULL OR einddatum >  GETDATE()
");

$query->execute();
$countries = $query->fetchAll(PDO::FETCH_ASSOC);

?>

<select class="borderless-input" id="input_country" name="country-register" required>
    <option value="" disabled selected>choose your country</option>
    <?php foreach($countries as $country) {
        echo '<option value="' . $country['naam_land'] . '">' . $country['naam_land'] . '</option>';
    }
    ?>
</select>

