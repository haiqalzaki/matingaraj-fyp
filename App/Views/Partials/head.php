<!DOCTYPE html>
<html lang="en" id="parent">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $data['tajuk'] ?> Page</title>
    <link rel="icon" type="image/x-icon" href="<?= $_ENV['HOME_URL'] ?>/image/matin.ico">
    <link rel="stylesheet" href="<?= $_ENV['HOME_URL'] ?>/library/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?= $_ENV['HOME_URL'] ?>/library/datatables/datatables.min.css">
    <link rel="stylesheet" href="<?= $_ENV['HOME_URL'] ?>/css/font.css">
    <link rel="stylesheet" href="<?= $_ENV['HOME_URL'] ?>/css/navbar.css">
    <link rel="stylesheet" href="<?= $_ENV['HOME_URL'] ?>/css/sidebar.css">
    <link rel="stylesheet" href="<?= $_ENV['HOME_URL'] ?>/css/<?= $data['css'] ?>.css">     
    <link rel="stylesheet" href="<?= $_ENV['HOME_URL'] ?>/library/bootstrap-icons/css/bootstrap-icons.css">
    <script> window.BASE_PATH = "<?= $_ENV['HOME_URL'] ?>"; </script>
    <script src="<?= $_ENV['HOME_URL'] ?>/js/helper/constant.js"></script>
    <script src="<?= $_ENV['HOME_URL'] ?>/js/helper/config.js"></script>
    <script src="<?= $_ENV['HOME_URL'] ?>/js/helper/function.js"></script>
    <script src="<?= $_ENV['HOME_URL'] ?>/library/table.js"></script>
    <script src="<?= $_ENV['HOME_URL'] ?>/library/datatables/datatables.min.js"></script>
    <script src="<?= $_ENV['HOME_URL'] ?>/library/chartjs/chart.umd.min.js"></script>
</head>
<body>
