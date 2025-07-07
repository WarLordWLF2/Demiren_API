<?php

include 'header.php';

// Goals for Admin Functions 
// Importance (From Top to Bottom)
// 1. Can book a customer into a room
// 2. Can cancel a booking
// 3. Can update a booking
// 4. Can view all booking

class FrontDesk_Functions
{
    // Views the Rooms that are currently Booked and it's details
    function view_booking()
    {
        include 'connection.php';

        $sql = "SELECT a.booking_id, CONCAT(d.customers_walk_in_fname, ' ', d.customers_walk_in_lname) AS fullname, 
                e.customers_online_username, a.booking_downpayment, c.booking_status_name, a.booking_checkin_dateandtime, 
                a.booking_checkout_dateandtime, a.booking_created_at FROM tbl_booking a 

                LEFT JOIN tbl_customers_walk_in d ON a.customers_walk_in_id = d.customers_walk_in_id
                LEFT JOIN tbl_customers_online e ON a.customers_id = e.customers_online_id
                LEFT JOIN tbl_booking_history b ON a.booking_id = b.booking_id
                LEFT JOIN tbl_booking_status c ON b.status_id = c.booking_status_id";

        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $rowCount = $stmt->rowCount();
        unset($stmt, $conn);

        return $rowCount > 0 ? json_encode($result) : 0;
    }

    // New Booking Method
    // Inserts it inside the table, tbl_status_booking
    // Should record the change of status
    function recBooking_Status($data)
    {
        include 'connection.php';

        try {
            // Reminder to accept Employee ID
            // $emp_id = intval($data["emp_id"]);
            $book_id = intval($data["booking_id"]);
            $status_id = intval($data["booking_status_id"]);

            $stmt  = $conn->prepare(
                "INSERT INTO tbl_booking_history (booking_id, employee_id, status_id,updated_at)
                VALUES (:booking_id, 1, :status_id, NOW())");

            $stmt->bindParam(":booking_id", $book_id);
            // $stmt->bindParam(":employee_id", $emp_id);
            $stmt->bindParam(":status_id", $status_id);
            $stmt->execute();

            $rowCount = $stmt->rowCount();
            unset($stmt, $conn);

            return $rowCount > 0 ? json_encode(["success" => true]) : json_encode(["success" => false]);
        } catch (PDOException $e) {
            return json_encode(["success" => false, "error" => $e->getMessage()]);
        }
    }

    // Add Guests
    function customerWalkIn($data)
    {
        include "connection.php";

        try {
            $conn->beginTransaction();

            // Insert walk-in customer
            $stmt = $conn->prepare("
                INSERT INTO tbl_customers_walk_in 
                    (customers_walk_in_fname, customers_walk_in_lname, customers_walk_in_email, customers_walk_in_phone_number) 
                VALUES 
                    (:customers_walk_in_fname, :customers_walk_in_lname, :customers_walk_in_email, :customers_walk_in_phone_number)
            ");
            $stmt->bindParam(":customers_walk_in_fname", $data["customers_walk_in_fname"]);
            $stmt->bindParam(":customers_walk_in_lname", $data["customers_walk_in_lname"]);
            $stmt->bindParam(":customers_walk_in_email", $data["customers_walk_in_email"]);
            $stmt->bindParam(":customers_walk_in_phone_number", $data["customers_walk_in_phone_number"]);
            $stmt->execute();
            $walkInCustomerId = $conn->lastInsertId();

            // Insert booking
            $stmt = $conn->prepare("
                INSERT INTO tbl_booking 
                    (customers_id, customers_walk_in_id, booking_status_id, booking_downpayment, booking_checkin_dateandtime, booking_checkout_dateandtime, booking_created_at) 
                VALUES 
                    (NULL, :customers_walk_in_id, 2, :booking_downpayment, :booking_checkin_dateandtime, :booking_checkout_dateandtime, NOW())
            ");
            $stmt->bindParam(":customers_walk_in_id", $walkInCustomerId);
            $stmt->bindParam(":booking_downpayment", $data["booking_downpayment"]);
            $stmt->bindParam(":booking_checkin_dateandtime", $data["booking_checkin_dateandtime"]);
            $stmt->bindParam(":booking_checkout_dateandtime", $data["booking_checkout_dateandtime"]);
            $stmt->execute();
            $bookingId = $conn->lastInsertId();

            // Insert into tbl_booking_room based on room quantity
            $roomtype_id = $data["roomtype_id"];
            $room_count = intval($data["room_count"]);

            for ($i = 0; $i < $room_count; $i++) {
                $stmt = $conn->prepare("
                    INSERT INTO tbl_booking_room 
                        (booking_id, roomtype_id, roomnumber_id) 
                    VALUES 
                        (:booking_id, :roomtype_id, NULL)
                ");
                $stmt->bindParam(":booking_id", $bookingId);
                $stmt->bindParam(":roomtype_id", $roomtype_id);
                $stmt->execute();
            }

            $conn->commit();
            return 1;
        } catch (PDOException $e) {
            $conn->rollBack();
            return 0;
        }
    }

    // Front Desk Checks In and Checks Out
    function visitor_logs($data)
    {
        include 'connection.php';
        $decodeData = json_decode($data, true);

        $sql = "INSERT INTO tbl_guests(visitorlogs_guest_id, visitorlogs_visitorname , visitorlogs_purpose , visitorlogs_checkin_time,
                visitorlogs_checkout_time)
                VALUES(:guest, :visitor_name, :purpose, :check_in, :check_out)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":guest", $decodeData[""]);
        $stmt->bindParam(":visitor_name", $decodeData[""]);
        $stmt->bindParam(":purpose", $decodeData[""]);
        $stmt->bindParam(":check_in", $decodeData[""]);
        $stmt->bindParam(":check_out", $decodeData[""]);
        $stmt->execute();

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        unset($stmt, $conn);
        if ($result) {
            json_encode(["response" => true, "message" => "Successfully Added New Log"]);
        }
    }

    // Can change Check In Check Out
    function change_visitor_logs($data)
    {
        include 'connection.php';
        $decodeData = json_decode($data, true);

        $sql = "UPDATE tbl_guests 
                SET visitorlogs_guest_id = :guest, visitorlogs_visitorname = :visitor_name, visitorlogs_purpose = :purpose, 
                visitorlogs_checkin_time = :check_in, visitorlogs_checkout_time = :check_out)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":guest", $decodeData[""]);
        $stmt->bindParam(":visitor_name", $decodeData[""]);
        $stmt->bindParam(":purpose", $decodeData[""]);
        $stmt->bindParam(":check_in", $decodeData[""]);
        $stmt->bindParam(":check_out", $decodeData[""]);
        $stmt->execute();

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        unset($stmt, $conn);
        if ($result) {
            json_encode(["response" => true, "message" => "Successfully Updated Log"]);
        }
    }
}


$demiren_FrontDesk = new FrontDesk_Functions();
$method = isset($_POST["method"]) ? $_POST["method"] : 0;
$json = isset($_POST["json"]) ? json_decode($_POST["json"], true) : 0;

switch ($method) {
    case 'view-reservations':
        echo $demiren_FrontDesk->view_booking();
        break;
    case 'record-booking-status':
        echo $demiren_FrontDesk->recBooking_Status($json);
        break;

    // Guests
    case 'customer-walkIn':
        echo $demiren_FrontDesk->customerWalkIn($json);
        break;

    // Room Availablity
    case 'available-rooms':
        break;
    case 'edit-room':
        break;
    default:
        echo "Method Unavailable...";
        break;
}
