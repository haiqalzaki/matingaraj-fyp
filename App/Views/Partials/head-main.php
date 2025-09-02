<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $data['tajuk'] ?> Page</title>
    <link rel="icon" type="image/x-icon" href="<?= $_ENV['HOME_URL'] ?>/image/matin.ico">
    <link rel="stylesheet" href="<?= $_ENV['HOME_URL'] ?>/library/bootstrap/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@300;400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= $_ENV['HOME_URL'] ?>/css/font.css">
    <link rel="stylesheet" href="<?= $_ENV['HOME_URL'] ?>/css/<?= $data['css'] ?>.css">   
    <script src="<?= $_ENV['HOME_URL'] ?>/js/helper/constant.js"></script>
    <script src="<?= $_ENV['HOME_URL'] ?>/js/helper/function.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<style>
body, html {
    margin: 0;
    padding: 0;
    height: 100%;
    background-color:rgb(230, 242, 255);
}

.container {
    height: 100%; 
}

</style>
<body>
