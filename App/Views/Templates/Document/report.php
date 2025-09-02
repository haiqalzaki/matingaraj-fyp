<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= $_ENV['HOME_URL'] ?>/css/document-report.css">   
    <title>Quotation</title>
</head>
<body>
    <div class="wrapper_header">
        <table class="table_header">
            <tr>
                <td class="left_column_header">
                    <h2 class="bg-warning"><strong>MATIN GARAJ REPORT</strong></h2>
                    <p><i>We Build We Trust</i></p>
                </td>
                <td class="right_column_header">
                    <img src=<?= $data['imagePath'] ?> width="200" alt=""> <!-- IMPORTANT FOR IMAGE -->
                </td>
            </tr>
        </table>
    </div>

    <div class="wrapper_products">
        <table class="table_products">
            <thead>
                <tr>
                    <th class="index_column_title">No.</th>
                    <th class="index_column_title">Customer</th>
                    <th class="index_column_title">Bike</th>
                    <th class="index_column_title">Plate</th>
                    <th class="index_column_title">Service Type</th>
                    <th class="index_column_title">Service Status</th>
                    <th class="index_column_title">Remarks</th>
                    <th class="index_column_title">Markup(RM)</th>
                    <th class="index_column_title">Item(RM)</th>
                    <th class="index_column_title">Total(RM)</th>
                    <th class="index_column_title">Created</th>
                    <th class="index_column_title">Updated</th>
                </tr>
            </thead>
            <tbody>
                <?php $counter = 1; ?>
                <?php foreach ($data['rows'] as $row): ?>
                    <?php $name = "Muhammad Haiqal bin Mohd Zaki bin Samat"; ?>
                    <!-- Can explode name to get only 2 first -->
                    <?php if (mb_strlen($name) > 25): ?>
                        <?php $name = mb_strimwidth($name, 0, 25, "..."); ?>
                    <?php endif; ?>
                    <?php if (mb_strlen($row['t_remark']) > 30): ?>
                        <?php $row['t_remark'] = mb_strimwidth($row['t_remark'], 0, 30, "..."); ?>
                    <?php endif; ?>
                    <?php if (mb_strlen($row['t_bike']) > 30): ?>
                        <?php $row['t_bike'] = mb_strimwidth($row['t_bike'], 0, 30, "..."); ?>
                    <?php endif; ?>
                    <tr>
                        <td class="index_column_products"><?= $counter ?></td>
                        <td class="index_column_products"><?= $name ?></td>
                        <td class="index_column_products"><?= $row['t_bike']; ?></td>
                        <td class="index_column_products"><?= $row['t_plate'] ?></td>
                        <td class="index_column_products"><?= $row['t_serviceType'] ?></td>             
                        <td class="index_column_products"><?= $row['t_serviceStatus'] ?></td>             
                        <td class="index_column_products"><?= $row['t_remark'] ?></td>             
                        <td class="index_column_products"><?= $row['t_markupTotal'] ?></td>             
                        <td class="index_column_products"><?= $row['t_itemTotal'] ?></td>             
                        <td class="index_column_products"><?= $row['t_totalPrice'] ?></td>             
                        <td class="index_column_products"><?= $row['createdOn'] ?></td>             
                        <td class="index_column_products"><?= $row['updatedOn'] ?></td>             
		            </tr>
                    <?php $counter = $counter + 1 ?>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>