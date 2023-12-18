<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Methods: *");

// include 'DbConnect.php';
// $objDb = new DbConnect;
// $conn = $objDb->connect();

$method = $_SERVER['REQUEST_METHOD'];
switch($method) {
    case "GET":
        $sql = "SELECT * FROM license_plates";
        // $path = explode('/', $_SERVER['REQUEST_URI']);
        // if(isset($path[3]) && is_numeric($path[3])) {
        //     $sql .= " WHERE id = :id";
        //     $stmt = $conn->prepare($sql);
        //     $stmt->bindParam(':id', $path[3]);
        //     $stmt->execute();
        //     $users = $stmt->fetch(PDO::FETCH_ASSOC);
        // } else {
        //     $stmt = $conn->prepare($sql);
        //     $stmt->execute();
        //     $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
        // }

        // echo json_encode($users);
        $con = mysqli_connect("localhost:3308", "root", "");
        mysqli_select_db($con, "react-login");
        $result = mysqli_query($con, "SELECT * FROM license_plates");
        $nums = mysqli_num_rows($result);
        $rs = mysqli_fetch_array($result);
        // if ($nums >= 1) {
        //     http_response_code(200);
        //     $outp = '{"frame_nmr":"' . $rs["frame_nmr"] . '",';
        //     $outp .= '"car_id":"' . $rs["car_id"] . '",';
        //     $outp .= '"license_plate_bbox":"' . $rs["license_plate_bbox"] . '",';
        //     $outp .= '"license_plate_bbox_score":"' . $rs["license_plate_bbox_score"] . '",';
        //     $outp .= '"license_number":"' . $rs["license_number"] . '",';
        //     $outp .= '"license_number_score":"' . $rs["license_number_score"] . '",';
        //     $outp .= '"registered":"' . $rs["registered"] . '",';
        //     $outp .= '"timestamp":"' . $rs["timestamp"] . '",';
        //     echo $outp;
        // } else {
        //     echo $password;
        //     echo $email;
        //     http_response_code(202);
        // }
        // while ($row = mysqli_fetch_array($result, MYSQLI_BOTH)) {
        //     $_SESSION['email'] = $row['email'];
        //     $flag = TRUE;
        // }
        $rows=array();
        while($row = mysqli_fetch_array($result))
            {
                $rows[]=$row;
            }
        echo json_encode($rows);
        break;
    case "POST":
        $user = json_decode( file_get_contents('php://input') );
        // $sql = "INSERT INTO users(id, name, email, mobile, created_at) VALUES(null, :name, :email, :mobile, :created_at)";
        
        $sql = "INSERT INTO users(frame_nmr, car_id, license_plate_bbox, license_number, license_number_score, registered) VALUES(null, :frame_nmr, :car_id, :license_plate_bbox, :license_number, :license_number_score, :registered)";
        $stmt = $conn->prepare($sql);
        $created_at = date('Y-m-d');
        $stmt->bindParam(':frame_nmr ', $user->frame_nmr);
        $stmt->bindParam(':car_id', $user->car_id);
        $stmt->bindParam(':license_plate_bbox', $user->license_plate_bbox);
        $stmt->bindParam(':license_number', $user->license_number);
        $stmt->bindParam(':license_number_score', $user->license_number_score);
        $stmt->bindParam(':registered', $user->registered);


        if($stmt->execute()) {
            $response = ['status' => 1, 'message' => 'Record created successfully.'];
        } else {
            $response = ['status' => 0, 'message' => 'Failed to create record.'];
        }
        echo json_encode($response);
        break;

    case "PUT":
        $user = json_decode( file_get_contents('php://input') );
        $sql = "UPDATE users SET name= :name, email =:email, mobile =:mobile, updated_at =:updated_at WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $updated_at = date('Y-m-d');
        $stmt->bindParam(':id', $user->id);
        $stmt->bindParam(':name', $user->name);
        $stmt->bindParam(':email', $user->email);
        $stmt->bindParam(':mobile', $user->mobile);
        $stmt->bindParam(':updated_at', $updated_at);

        if($stmt->execute()) {
            $response = ['status' => 1, 'message' => 'Record updated successfully.'];
        } else {
            $response = ['status' => 0, 'message' => 'Failed to update record.'];
        }
        echo json_encode($response);
        break;

    case "DELETE":
        $sql = "DELETE FROM parking WHERE car_id = :id";
        $path = explode('/', $_SERVER['REQUEST_URI']);

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $path[3]);

        if($stmt->execute()) {
            $response = ['status' => 1, 'message' => 'Record deleted successfully.'];
        } else {
            $response = ['status' => 0, 'message' => 'Failed to delete record.'];
        }
        echo json_encode($response);
        break;
}