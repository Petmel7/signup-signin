1 Зробити щоб відображало всіх користувачів кромі авторизованого

// function handleGetRequest()
// {
// try {
// $conn = getPDO();

// $sql = "SELECT name, avatar FROM users";

// $stmt = $conn->prepare($sql);
// $stmt->execute();

// $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

// header('Content-Type: application/json');
// echo json_encode($users);

// } catch (PDOException $e) {
//         echo json_encode(['error' => $e->getMessage()]);
//     } finally {
//         if ($conn !== null) {
// $conn = null;
// }
// }
// }

// handleGetRequest();

ось що в log
[2024-01-19T18:53:05.923104+01:00] my_log.WARNING: warning $loggedInUsername [null] []
[2024-01-19T18:53:05.924409+01:00] my_log.ERROR: error $loggedInUsername [null] []
