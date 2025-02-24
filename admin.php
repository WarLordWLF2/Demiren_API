<?php

include 'header.php';


class Admin_Functions
{

    // ------------------------------------------------------- No Table For Admin Yet, needs Changes ------------------------------------------------------- //
    // Login & Signup ???
    function admin_login($data)
    {
        include "connection.php";

        $sql = "";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":email", $data[""]);
        $stmt->bindParam(":password", $data[""]);
    }

    // -------- View Data Here -------- //

    // Check In & Check Out ✔
    function view_checkin_checkout()
    {
        include "connection.php";

        $sql = "SELECT in_out.checkin_checkout_id, CONCAT(guests.guests_user_fname, ' ' ,guests.guests_user_lname) AS guest, roomType.roomtype_name, in_out.checkin_date, 
                    in_out.checkin_time, in_out.checkout_date, in_out.checkout_time, in_out.checkin_checkout_status FROM tbl_checkin_checkout in_out
                RIGHT JOIN tbl_guests guests ON in_out.checkin_checkout_guest_id = guests.guests_id
                INNER JOIN tbl_roomtype roomType ON in_out.checkin_checkout_roomtype_id = roomType.roomtype_id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $rowCount = $stmt->rowCount();

        return $rowCount > 0 ? json_encode($result) : 0;
    }

    // Room Price - Update, Delete
    function view_room_price()
    {
        include "connection.php";

        $sql = "SELECT price.price_id, room_type.roomtype_name, price.price_room FROM tbl_price price
                INNER JOIN tbl_roomtype room_type ON price.price_roomtype_id = room_type.roomtype_id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $rowCount = $stmt->rowCount();
        unset($stmt, $pdo);

        return $rowCount > 0 ? json_encode($result) : 0;
    }

    // Guests
    function view_all_guests()
    {
        include "connection.php";

        $sql = "SELECT * FROM tbl_guests";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $rowCount = $stmt->rowCount();
        unset($stmt, $pdo);

        return $rowCount > 0 ? json_encode($result) : 0;
    }

    // Payments ???
    function payments_history()
    {
        include "connection.php";

        $sql = "SELECT * FROM tbl_payments";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $rowCount = $stmt->rowCount();
        unset($stmt, $pdo);

        return $rowCount > 0 ? json_encode($result) : 0;
    }

    // Reservation Details
    function view_reservation_online()
    {
        include "connection.php";

        $sql = "SELECT ro.reservation_online_id, CONCAT(guests.guests_user_fname, ' ', guests.guests_user_lname) AS guest_fullname, 
                ro.reservation_online_num_of_guest AS guest_amt, ro.reservation_online_adult AS adult, ro.reservation_online_children AS children, 
                roomType.roomtype_name AS room_type, ro.reservation_online_total AS total, ro.reservation_online_downpayment AS downpayment, 
                ro.reservation_online_balance AS balance, sched.timeanddate_date_arrival AS arrival FROM tbl_reservation_online ro
                INNER JOIN tbl_guests guests ON ro.reservation_online_guest_id = guests.guests_id
                INNER JOIN tbl_roomtype roomType ON ro.reservation_online_roomtype_id = roomType.roomtype_id
                INNER JOIN tbl_timeanddate sched ON ro.reservation_online_timeanddate_id = sched.timeanddate_id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $rowCount = $stmt->rowCount();
        unset($stmt, $pdo);

        return $rowCount > 0 ? json_encode($result) : 0;
    }

    // Rooms
    function view_rooms()
    {
        include "connection.php";

        $sql = "SELECT rooms.rooms_status, rooms.rooms_price_per_day, rooms.rooms_type_availability, roomType.roomtype_name FROM tbl_rooms rooms
                INNER JOIN tbl_roomtype roomType ON rooms.roomtype_id = roomType.roomtype_id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $rowCount = $stmt->rowCount();
        unset($stmt, $pdo);

        return $rowCount > 0 ? json_encode($result) : 0;
    }

    // Beds
    function view_rooms_beds()
    {
        include "connection.php";

        $sql = "SELECT * FROM tbl_room_beds";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $rowCount = $stmt->rowCount();
        unset($stmt, $pdo);

        return $rowCount > 0 ? json_encode($result) : 0;
    }

    // Room Capacity
    function view_rooms_capacity()
    {
        include "connection.php";

        $sql = "SELECT room_amt.room_capacity_id, roomType.roomtype_name, room_amt.room_capacity_max_guests FROM tbl_room_capacity room_amt
                INNER JOIN tbl_roomtype roomType ON room_amt.room_capacity_roomtype_id = roomType.roomtype_id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $rowCount = $stmt->rowCount();
        unset($stmt, $pdo);

        return $rowCount > 0 ? json_encode($result) : 0;
    }

    // All Room Sizes ???
    function view_room_sizes()
    {
        include "connection.php";

        $sql = "SELECT * FROM tbl_sizes";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $rowCount = $stmt->rowCount();
        unset($stmt, $pdo);

        return $rowCount > 0 ? json_encode($result) : 0;
    }

    // Views All Staff
    function view_all_staff()
    {
        include "connection.php";

        $sql = "SELECT * FROM tbl_staff";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $rowCount = $stmt->rowCount();
        unset($stmt, $pdo);

        return $rowCount > 0 ? json_encode($result) : 0;
    }

    // Views All Guest Scedule
    function view_guest_sched()
    {
        include "connection.php";

        $sql = "SELECT sched.timeanddate_id, sched.timeanddate_time_arrival, sched.timeanddate_date_arrival, 
                    sched.timeanddate_created_at, sched.timeanddate_updated_at, child_age.child_age FROM tbl_timeanddate sched
                INNER JOIN tbl_guests guests ON sched.timeanddate_guest_id = guests.guests_id
                LEFT JOIN tbl_childage child_age ON sched.timeanddate_child_age_id = child_age.childage_id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $rowCount = $stmt->rowCount();
        unset($stmt, $pdo);

        return $rowCount > 0 ? json_encode($result) : 0;
    }

    // Views All Users with/without Reservations ???
    function view_visit_logs()
    {
        include "connection.php";

        $sql = "";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $rowCount = $stmt->rowCount();
        unset($stmt, $pdo);

        return $rowCount > 0 ? json_encode($result) : 0;
    }

    // -------- Create New Data -------- //
    // Add New Rooms
    function add_rooms($data)
    {
        include "connection.php";

        $sql = "INSERT INTO tbl_rooms(rooms_status, rooms_price_per_day, rooms_type_availability, roomtype_id) 
                VALUES(:status, :price, :available, :roomType)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":status", $data[""]);
        $stmt->bindParam(":price", $data[""]);
        $stmt->bindParam(":available", $data[""]);
        $stmt->bindParam(":roomType", $data[""]);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $rowCount = $stmt->rowCount();
        unset($stmt, $pdo);

        return $rowCount > 0 ? json_encode($result) : 0;
    }

    // Add New Beds
    function add_beds($data)
    {
        include "connection.php";

        $sql = "INSERT INTO tbl_room_beds(room_beds) 
                VALUES(:beds)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":beds", $data[""]);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $rowCount = $stmt->rowCount();
        unset($stmt, $pdo);

        return $rowCount > 0 ? json_encode($result) : 0;
    }

    // Add New Rooms with Capacities
    function add_room_capacity($data)
    {
        include "connection.php";

        $sql = "INSERT INTO tbl_room_capacity(room_capacity_roomtype_id, room_capacity_max_guests) 
                VALUES(:roomType, :amt)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":roomType", $data[""]);
        $stmt->bindParam(":amt", $data[""]);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $rowCount = $stmt->rowCount();
        unset($stmt, $pdo);

        return $rowCount > 0 ? json_encode($result) : 0;
    }

    // Add New Sizes
    function add_size($data)
    {
        include "connection.php";

        $sql = "INSERT INTO tbl_sizes(room_sizes) 
                VALUES(:sizes)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":sizes", $data[""]);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $rowCount = $stmt->rowCount();
        unset($stmt, $pdo);

        return $rowCount > 0 ? json_encode($result) : 0;
    }

    // Add New Staff
    function add_staff($data)
    {
        include "connection.php";

        $sql = "INSERT INTO tbl_staff(staff_fname, staff_lname, staff_time_in, staff_time_out, staff_phone_number, staff_email, staff_shift ) 
                VALUES(:fname, :lname, :time_in, :time_out, :phone, :email, :shift)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":fname", $data[""]);
        $stmt->bindParam(":lname", $data[""]);
        $stmt->bindParam(":time_in", $data[""]);
        $stmt->bindParam(":time_out", $data[""]);
        $stmt->bindParam(":phone", $data[""]);
        $stmt->bindParam(":email", $data[""]);
        $stmt->bindParam(":shift", $data[""]);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $rowCount = $stmt->rowCount();
        unset($stmt, $pdo);

        return $rowCount > 0 ? json_encode($result) : 0;
    }


    // -------- Update Existing Data -------- //
    // Updates Current Price
    function upd_price($data)
    {
        include "connection.php";

        $sql = "UPDATE SET WHERE";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":fname", $data[""]);
        $stmt->bindParam(":lname", $data[""]);
        $stmt->bindParam(":time_in", $data[""]);
        $stmt->bindParam(":time_out", $data[""]);
        $stmt->bindParam(":phone", $data[""]);
        $stmt->bindParam(":email", $data[""]);
        $stmt->bindParam(":shift", $data[""]);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $rowCount = $stmt->rowCount();
        unset($stmt, $pdo);

        return $rowCount > 0 ? json_encode($result) : 0;
    }

    // Updates Current Rooms
    function upd_rooms($data) {}

    // Updates Current Room Capacity
    function upd_room_capacity($data) {}

    // Updates Current Rooms Sizes
    function upd_sizes($data) {}

    // Updates Staff Info
    function upd_staff($data) {}


    // -------- Delete Data -------- //
    // Removes Guests
    function rm_guests() {}

    // Removes Prices
    function rm_set_price() {}

    // Removes Rooms
    function rm_rooms() {}

    // Removes Beds
    function rm_beds() {}

    // Removes Capacity
    function rm_capacity() {}

    // Removes Sizes
    function rm_sizes() {}

    // Removes Staff
    function rm_staff() {}
}


$admin_class = new Admin_Functions();

$methodType = isset($_POST["method"]) ? $_POST["method"] : 0;
$jsonData = isset($_POST["json"]) ? json_decode($_POST["json"], true) : 0;


switch ($methodType) {
        // --------------------------------- For Viewing Data or Login --------------------------------- //
    case "login-data":
        echo $admin_class->admin_login($jsonData);
        break;

    case "view-customers":
        echo json_encode(["message" => "Successfully Retrieved Data"]);
        break;

    case "view_checkins_outs":
        echo $admin_class->view_checkin_checkout();
        break;

    case "view_prices":
        echo $admin_class->view_room_price();
        break;

    case "view_all_guests":
        echo $admin_class->view_all_guests();
        break;

    case "view_rsrv_online":
        echo $admin_class->view_reservation_online();
        break;

    case "view_rooms":
        echo $admin_class->view_rooms();
        break;

    case "view_beds":
        echo $admin_class->view_rooms_beds();
        break;

    case "view_capacities":
        echo $admin_class->view_rooms_capacity();
        break;

    case "view_staff":
        echo $admin_class->view_all_staff();
        break;

    case "view_scedule":
        echo $admin_class->view_guest_sched();
        break;


        // --------------------------------- For Adding Data --------------------------------- //
    case "add":
        echo json_encode(["message" => "Successfully Added New Data", "data" => $jsonData]);
        break;

    case "add":
        echo json_encode(["message" => "Successfully Added New Data", "data" => $jsonData]);
        break;


        // --------------------------------- For Updating Data --------------------------------- //
    case "update":
        echo json_encode(["message" => "Successfully Updated Data", "data" => $jsonData]);
        break;

    case "update":
        echo json_encode(["message" => "Successfully Updated Data", "data" => $jsonData]);
        break;


        // --------------------------------- For Deleting Data --------------------------------- //
    case "delete":
        echo json_encode(["message" => "Successfully Removed Data", "data" => $jsonData]);
        break;

    case "delete":
        echo json_encode(["message" => "Successfully Removed Data", "data" => $jsonData]);
        break;


    default:
        echo json_encode(["message" => "Could not find anything"]);
}
