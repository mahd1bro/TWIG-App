<?php
echo "<h1>Form Test</h1>";

if ($_POST) {
    echo "<p>Form submitted successfully!</p>";
    echo "<pre>";
    print_r($_POST);
    echo "</pre>";
} else {
    echo '
    <form method="POST">
        <input type="text" name="test_field" value="Test Value">
        <button type="submit">Test Submit</button>
    </form>
    ';
}
?>