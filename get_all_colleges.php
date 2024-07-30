<?php
include 'assets/db_confing.php';

// Assuming you have established a connection to your database and stored it in $conn
header('Content-Type: application/json');


if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    
    if ($id == 1) {
        // Show all colleges if URL contains 'universities', otherwise limit to 9
        $collegeQuery = "SELECT colleges.*, college_details.city, states.name as state_name, college_details.created_at
                         FROM college_course_manage
                         LEFT JOIN colleges ON college_course_manage.college_id = colleges.id
                         LEFT JOIN college_details ON college_details.college_id = colleges.id
                         LEFT JOIN states ON college_details.state_id = states.id
                         GROUP BY college_course_manage.college_id";
    } else {
        // Show colleges based on selected course and limit to 9
        $collegeQuery = "SELECT colleges.*, college_details.city, states.name as state_name, college_details.created_at
                         FROM college_course_manage
                         LEFT JOIN colleges ON college_course_manage.college_id = colleges.id
                         LEFT JOIN college_details ON college_details.college_id = colleges.id
                         LEFT JOIN states ON college_details.state_id = states.id
                         WHERE college_course_manage.course_id = $id
                         GROUP BY college_course_manage.college_id";
    }

    $collegeResult = $conn->query($collegeQuery);

    $colleges = [];

    while ($collegeData = mysqli_fetch_assoc($collegeResult)) {
        $colleges[] = $collegeData;
    }

    echo json_encode($colleges);
} else {
    echo json_encode([]);
}
?>
