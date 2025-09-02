<!DOCTYPE html>
<html lang="en" id="parent">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $data['tajuk'] ?> Page</title>
    <link rel="icon" type="image/x-icon" href="<?= $_ENV['HOME_URL'] ?>/image/matin.ico">
    <link rel="stylesheet" href="<?= $_ENV['HOME_URL'] ?>/library/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css"> <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="<?= $_ENV['HOME_URL'] ?>/library/datatables/datatables.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@300;400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= $_ENV['HOME_URL'] ?>/css/font.css">
    <link rel="stylesheet" href="<?= $_ENV['HOME_URL'] ?>/css/navbar.css">
    <link rel="stylesheet" href="<?= $_ENV['HOME_URL'] ?>/css/sidebar.css">
    <link rel="stylesheet" href="<?= $_ENV['HOME_URL'] ?>/css/<?= $data['css'] ?>.css">     
    <script src="<?= $_ENV['HOME_URL'] ?>/js/helper/constant.js"></script>
    <script src="<?= $_ENV['HOME_URL'] ?>/js/helper/config.js"></script>
    <script src="<?= $_ENV['HOME_URL'] ?>/js/helper/function.js"></script>
    <script src="<?= $_ENV['HOME_URL'] ?>/library/table.js"></script>
    <script src="<?= $_ENV['HOME_URL'] ?>/library/datatables/datatables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
