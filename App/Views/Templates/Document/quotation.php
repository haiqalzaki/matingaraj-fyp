<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= $_ENV['HOME_URL'] ?>/css/document.css">   
    <title>Quotation</title>
</head>
<body>
    <div class="title_header">
        <h1><?= $data['title'] ?></h1>
        <h1></h1>
    </div>
    <div class="wrapper_header">
        <table class="table_header">
            <tr>
                <td class="left_column_header">
                    <h2 class="bg-warning"><strong>MATIN GARAJ</strong></h2>
                    <p><i>We Build We Trust</i></p>
                </td>
                <td class="right_column_header">
                    <img src="<?= $_ENV['BASE_ROOT'] ?>/awam/image/logo.png" width="200" alt="">
                </td>
            </tr>
            <tr>
                <td class="left_column">
                    <p><b>Address: </b></p>
                    <p>Lot 33G Jalan Orkid 6 Taman Orkid, Pekan Batu 9 Cheras, 43200 Cheras, Selangor</p>
                    <p>014-6071676</p>
                </td>
            </tr>
            <tr>
                <td class="left_column_bill">
                    <p><b>Billed to:  </b></p>
                    <p><?= $data['cx']['name'] ?></p>
                    <p><?= $data['cx']['phone'] ===  'Tiada' ? '-' : $data['cx']['phone']; ?></p>
		    <p><?= $data['cx']['email'] ===  'Tiada' ? '-' : $data['cx']['email']; ?></p>
                </td>
                <td class="right_column" style="vertical-align: bottom;">
                    <p><b>Date Issued:</b> <?= $data['date']; ?></p>   
                </td>
            </tr>
        </table>
    </div>

    <div class="wrapper_divider">
        <p>Estimated Cost: </p>
    </div>

    <div class="wrapper_products">
        <table class="table_products">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Expected Items</th>
                    <th>Quantity</th>
                    <th>Amount</th>
                </tr>
            </thead>
            <tbody>
                <?php $counter = 1; ?>
                <?php foreach ($data['barang'] as $barang): ?>
                    <tr>
                        <td class="index_column_products"><?= $counter ?></td>
                        <td class="left_column_products"><?= $barang['ti_name']; ?></td>
                        <td class="mid_column_products"><?= $barang['ti_quantity'] ?></td>
                        <td class="right_column_products">RM<?= number_format($barang['ti_priceItemCum'], 2); ?></td>                    
		    </tr>
                    <?php $counter++ ?>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <td style="border: none;"></td>
                    <td style="border: none;"></td>
                    <td style="border-bottom: none; border-left: none; text-align: right; padding-right: 10px;"><b>Service: </b></td>
                    <td class="right_column_products">RM<?= number_format($data['task']['markuptotal'], 2); ?></td>
                </tr>
                <tr>
                    <td style="border: none;"></td>
                    <td style="border: none;"></td>
                    <td style="border-bottom: none; border-left: none; border-top: none; text-align: right; padding-right: 10px;"><b>Grand Total: </b></td>
                    <td class="right_column_products">RM<?= number_format($data['task']['hargatotal'], 2); ?></td>
                </tr>
            </tfoot>
        </table>
    </div>

    <div class="wrapper_footer">
        <h4>SUBJECT TO CHANGE</h4>
    </div>
</body>
</html>