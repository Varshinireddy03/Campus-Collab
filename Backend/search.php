<?php
$servername = "localhost";
$username = "root"; 
$password = ""; 
$dbname = "campus_collab";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if(isset($_POST['search'])) {
    $query = "%" . $_POST['search'] . "%";

    // Fetch Projects
    $sqlProjects = "SELECT id, name FROM projects WHERE name LIKE ?";
    $stmt = $conn->prepare($sqlProjects);
    $stmt->bind_param("s", $query);
    $stmt->execute();
    $resultProjects = $stmt->get_result();

    // Fetch Mentors
    $sqlMentors = "SELECT id, name FROM mentors WHERE name LIKE ?";
    $stmt = $conn->prepare($sqlMentors);
    $stmt->bind_param("s", $query);
    $stmt->execute();
    $resultMentors = $stmt->get_result();

    echo "<div class='result-category'>📂 Projects</div>";
    while ($row = $resultProjects->fetch_assoc()) {
        echo "<div class='search-item' data-type='project' data-id='{$row['id']}'><i>📂</i> {$row['name']}</div>";
    }

    echo "<div class='result-category'>👤 Mentors</div>";
    while ($row = $resultMentors->fetch_assoc()) {
        echo "<div class='search-item' data-type='mentor' data-id='{$row['id']}'><i>👤</i> {$row['name']}</div>";
    }
}

$conn->close();
?>
