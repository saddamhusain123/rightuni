<?php
include 'assets/db_confing.php';

// Assuming you have established a connection to your database and stored it in $conn
header('Content-Type: application/json');

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    
    // Get total count of colleges for the selected course
    $countQuery = "SELECT COUNT(DISTINCT colleges.id) as total
                   FROM college_course_manage
                   LEFT JOIN colleges ON college_course_manage.college_id = colleges.id
                   LEFT JOIN college_details ON college_details.college_id = colleges.id
                   LEFT JOIN states ON college_details.state_id = states.id
                   WHERE (colleges.status = 1 AND colleges.deleted = 0)
                   AND ($id = 1 OR college_course_manage.course_id = $id)";
    $countResult = $conn->query($countQuery);
    $totalCount = $countResult->fetch_assoc()['total'];

    // Show colleges based on selected course and limit to 9
    // $limit = "LIMIT 9";
    $collegeQuery = "SELECT colleges.*, college_details.city, states.name as state_name, college_details.created_at
                     FROM college_course_manage
                     LEFT JOIN colleges ON college_course_manage.college_id = colleges.id
                     LEFT JOIN college_details ON college_details.college_id = colleges.id
                     LEFT JOIN states ON college_details.state_id = states.id
                     WHERE colleges.status = 1
                       AND colleges.deleted = 0
                       AND ($id = 1 OR college_course_manage.course_id = $id)
                     GROUP BY college_course_manage.college_id
                     ORDER BY colleges.id DESC";
                     // ORDER BY colleges.id DESC $limit";
    
    $collegeResult = $conn->query($collegeQuery);

    $colleges = [];

    while ($collegeData = mysqli_fetch_assoc($collegeResult)) {
        $colleges[] = $collegeData;
    }

    echo json_encode(['colleges' => $colleges, 'totalCount' => $totalCount]);
} else {
    echo json_encode([]);
}
?>
