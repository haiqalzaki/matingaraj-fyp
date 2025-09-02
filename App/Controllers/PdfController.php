<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Helper\Utiliti;
use Mpdf\Mpdf;
use Mpdf\MpdfException;
use App\Helper\Logger;
use Mpdf\Output\Destination;
use mysqli;

class PDF extends Controller 
{
    public function index() 
    {
        if (!$this->isValidSession()) {
            header('Location: ' . $_ENV['HOME_URL'] . '/home');
            exit();
        }
        
        header('Location: ' . $_ENV['HOME_URL'] . '/home');
        exit();
    }

    public function quotation($params = []) 
    {
        if (!$this->isValidSession()) {
            header('Location: ' . $_ENV['HOME_URL'] . '/home');
            exit();
        }

        if (empty($params) || !is_numeric($params[0])) {
            header('Location: ' . $_ENV['HOME_URL'] . '/home');
            exit();
        }

        $taskID = (int) $params[0];

        $db = new mysqli($_ENV['DB_HOST'], $_ENV['DB_USER'], $_ENV['DB_PASS'], $_ENV['DB_NAME']);

        $sql = "SELECT * FROM garaj_task WHERE t_id = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param('i', $taskID);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
            $data['task']['id'] = $row['t_id'];
            $data['task']['pelangganid'] = $row['c_id'];
            $data['task']['motor'] = $row['t_bike'];
            $data['task']['plate'] = $row['t_plate'];
            $data['task']['servis'] = $row['t_serviceType'];
            $data['task']['status'] = $row['t_serviceStatus'];
            $data['task']['dicipta'] = $row['createdOn'];
            $data['task']['dikemaskini'] = $row['updatedOn'];
            $data['task']['markuptotal'] = $row['t_markupTotal'];
            $data['task']['barangtotal'] = $row['t_itemTotal'];
            $data['task']['hargatotal'] = $row['t_totalPrice'];
        }
        $stmt->close();

	$sql = "SELECT * FROM garaj_customer WHERE c_id = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param('i', $data['task']['pelangganid']);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
            $data['cx']['name'] = $row['c_name'];
            $data['cx']['email'] = $row['c_email'];
            $data['cx']['phone'] = $row['c_phone'];
        }
        $stmt->close();

        $sql = "SELECT * FROM garaj_task_item WHERE t_id = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("i", $taskID);
        $stmt->execute();

        $result = $stmt->get_result();
        
        $data['barang'] = [];

        if ($result->num_rows > 0) {
            while ($rows = $result->fetch_assoc()) {
                $data['barang'][] = $rows;
            }
        }
        $stmt->close();

        // Load semua data
        $data['title'] = "QUOTATION";
        $data['date'] = date("d/m/Y");
        $data['imagePath'] = $_ENV['BASE_ROOT'] . "/image/logo.png";
        
        // Mula buffer output
        ob_start();

        // Render page dan output ke var HTML untuk further parsing
        $this->view('Templates/Document/quotation', $data);
        $html = ob_get_contents();

        // Bersihkan/Reset output buffer
        ob_end_clean();

        // Mula HTML parsing dan output
        $mpdf = new Mpdf();
        
        try {
            $filename = "MatinQuotation_" . date("Ymd_His") . ".pdf";

            $mpdf->WriteHTML($html);
            $mpdf->Output($filename, Destination::DOWNLOAD);
        } catch (MpdfException $pdfError) {
            Logger::log($pdfError->getMessage());
            die("PDF Error!");
        }
    }

    public function receipt($params = []) 
    {
        if (!$this->isValidSession()) {
            header('Location: ' . $_ENV['HOME_URL'] . '/home');
            exit();
        }

        if (empty($params) || !is_numeric($params[0])) {
            header('Location: ' . $_ENV['HOME_URL'] . '/home');
            exit();
        }

        $taskID = (int) $params[0];

        $db = new mysqli($_ENV['DB_HOST'], $_ENV['DB_USER'], $_ENV['DB_PASS'], $_ENV['DB_NAME']);

        $sql = "SELECT * FROM garaj_task WHERE t_id = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param('i', $taskID);
        $stmt->execute();
        $result = $stmt->get_result();
        

        while ($row = $result->fetch_assoc()) {
            $data['task']['id'] = $row['t_id'];
            $data['task']['pelangganid'] = $row['c_id'];
            $data['task']['motor'] = $row['t_bike'];
            $data['task']['plate'] = $row['t_plate'];
            $data['task']['servis'] = $row['t_serviceType'];
            $data['task']['status'] = $row['t_serviceStatus'];
            $data['task']['dicipta'] = $row['createdOn'];
            $data['task']['dikemaskini'] = $row['updatedOn'];
            $data['task']['markuptotal'] = $row['t_markupTotal'];
            $data['task']['barangtotal'] = $row['t_itemTotal'];
            $data['task']['hargatotal'] = $row['t_totalPrice'];
        }
        $stmt->close();

	    $sql = "SELECT * FROM garaj_customer WHERE c_id = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param('i', $data['task']['pelangganid']);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
            $data['cx']['name'] = $row['c_name'];
            $data['cx']['email'] = $row['c_email'];
            $data['cx']['phone'] = $row['c_phone'];
        }
        $stmt->close();

        $sql = "SELECT * FROM garaj_task_item WHERE t_id = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("i", $taskID);
        $stmt->execute();

        $result = $stmt->get_result();
        
        $data['barang'] = [];

        if ($result->num_rows > 0) {
            while ($rows = $result->fetch_assoc()) {
                $data['barang'][] = $rows;
            }
        }
        $stmt->close();

        $sql = "SELECT createdOn, updatedOn FROM garaj_task_finance WHERE t_id = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("i", $taskID);
        $stmt->execute();

        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
            $data['task']['dicipta'] = $row['createdOn'];
            $data['task']['dikemaskini'] = $row['updatedOn'];
        }
        $stmt->close();

        $arrayTime = explode(" ", $data['task']['dikemaskini']);
        $date = strtotime($arrayTime[0]);
        
        $data['task']['dikemaskini'] = date('d/m/Y', $date);

        // Load semua data
        $data['title'] = "RECEIPT";
        $data['date'] = date("d/m/Y");
        $data['imagePath'] = $_ENV['BASE_ROOT'] . "/image/logo.png";
        
        // Mula buffer output
        ob_start();

        // Render page dan output ke var HTML untuk further parsing
        $this->view('Templates/Document/receipt', $data);
        $html = ob_get_contents();

        // Bersihkan/Reset output buffer
        ob_end_clean();

        // Mula HTML parsing dan output
        $mpdf = new Mpdf();
        
        try {
            $filename = "MatinReceipt_" . date("Ymd_His") . ".pdf";

            $mpdf->WriteHTML($html);
            $mpdf->Output($filename, Destination::DOWNLOAD);
        } catch (MpdfException $pdfError) {
            Logger::log($pdfError->getMessage());
            die("PDF Error!");
        }
    }

    // public function report($params = []) 
    // {
    //     // Get type of data generated (task OR customer OR inventory)
    //     // Join with related tables and get assoc_array
    //     // Get data from DATE FROM to DATE to
    //     // Get how many rows
    //     // Get latest or oldest

    //     // Get user name from session (for who generated)
    //     // Generate current date time

    //     // Mock Data
    //     $data['rows'] = 
    //     [
    //         [
    //             "t_id"            => 1,
    //             "c_id"            => 101,
    //             "t_bike"          => "Yamaha R15",
    //             "t_plate"         => "ABC1234",
    //             "t_serviceType"   => "Major",
    //             "t_serviceStatus" => "Pending",
    //             "t_remark"        => "Customer reported oil leakCustomer reported oil leakCustomer reported oil leak",
    //             "t_markupTotal"   => "150.00",
    //             "t_itemTotal"     => "350.00",
    //             "t_totalPrice"    => "500.00",
    //             "isDeleted"       => 0,
    //             "deletedOn"       => null,
    //             "createdOn"       => "2025-08-01 10:15:00",
    //             "updatedOn"       => "2025-08-01 10:15:00"
    //         ],
    //         [
    //             "t_id"            => 2,
    //             "c_id"            => 102,
    //             "t_bike"          => "Honda CBR250R",
    //             "t_plate"         => "XYZ5678",
    //             "t_serviceType"   => "Minor",
    //             "t_serviceStatus" => "Ongoing",
    //             "t_remark"        => "Brake pad replacementBrake pad replacementBrake pad replacement",
    //             "t_markupTotal"   => "50.00",
    //             "t_itemTotal"     => "200.00",
    //             "t_totalPrice"    => "250.00",
    //             "isDeleted"       => 0,
    //             "deletedOn"       => null,
    //             "createdOn"       => "2025-08-05 09:00:00",
    //             "updatedOn"       => "2025-08-05 09:30:00"
    //         ],
    //         [
    //             "t_id"            => 3,
    //             "c_id"            => 103,
    //             "t_bike"          => "Kawasaki Ninja 650",
    //             "t_plate"         => "JKL9999",
    //             "t_serviceType"   => "Modify",
    //             "t_serviceStatus" => "Completed",
    //             "t_remark"        => "Custom exhaust installation",
    //             "t_markupTotal"   => "300.00",
    //             "t_itemTotal"     => "1200.00",
    //             "t_totalPrice"    => "1500.00",
    //             "isDeleted"       => 0,
    //             "deletedOn"       => null,
    //             "createdOn"       => "2025-07-25 14:20:00",
    //             "updatedOn"       => "2025-07-26 16:45:00"
    //         ],
    //         [
    //             "t_id"            => 4,
    //             "c_id"            => 104,
    //             "t_bike"          => "Kawasaki Ninja 650 LIMITED EDTITION",
    //             "t_plate"         => "WA 2345 S",
    //             "t_serviceType"   => "Modify",
    //             "t_serviceStatus" => "Completed",
    //             "t_remark"        => "Custom exhaust installation",
    //             "t_markupTotal"   => "300.00",
    //             "t_itemTotal"     => "1200.00",
    //             "t_totalPrice"    => "1500.00",
    //             "isDeleted"       => 0,
    //             "deletedOn"       => null,
    //             "createdOn"       => "2025-07-25 14:20:00",
    //             "updatedOn"       => "2025-07-26 16:45:00"
    //         ]
    //     ];

    //     // Pagination Logic

    //     // Load semua data
    //     $data['title'] = "REPORT";
    //     $data['date'] = date("d/m/Y");
    //     $data['imagePath'] = $_ENV['BASE_ROOT'] . "/awam/image/logo.png";

    //     // Mula buffer output
    //     ob_start();

    //     // Render page dan output ke var HTML untuk further parsing
    //     $this->view('Templates/Document/report', $data);
    //     $html = ob_get_contents();

    //     // Bersihkan/Reset output buffer
    //     ob_end_clean();

    //     // Mula HTML parsing dan output
    //     $mpdf = new Mpdf([
    //         'format' => 'A4-L'
    //     ]);

    //     try {
    //         $filename = "MatinReport_" . date("Ymd_His") . ".pdf";

    //         $mpdf->WriteHTML($html);
    //         $mpdf->Output($filename, Destination::DOWNLOAD);
    //     } catch (MpdfException $pdfError) {
    //         Logger::log($pdfError->getMessage());
    //         die("PDF Error!");
    //     }
    // }
}