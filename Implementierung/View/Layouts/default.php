<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title><?php echo $this->title; ?></title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="/materialize/css/style.css" media="screen,projection"/>
    <link rel="stylesheet" type="text/css" href="/custom/css/custom.css" media="screen,projection"/>
    <?php
    if (isset($this->styles)) {
        foreach ($this->styles as $style) {
            echo '<link rel="stylesheet" type="text/css" href="/custom/css/' . $style . '.css" media="screen,projection" />';
        }
    }
    ?>
    <script type="text/javascript" src="/materialize/js/materialize.min.js"></script>
    <?php
    if (isset($this->scripts)) {
        foreach ($this->scripts as $script) {
            echo '<script type="text/javascript" src="/custom/js/' . $script . '.js"></script>';
        }
    }
    ?>
    <script type="text/javascript" src="/custom/js/custom.js"></script>
</head>
<body>
<?php
require($this->header);
?>

<?php
if(file_exists($this->main)) {
    require($this->main);
}else{
    echo '<main class="container"></main>';
}
?>
<?php
require($this->footer);
?>
</body>
</html>
