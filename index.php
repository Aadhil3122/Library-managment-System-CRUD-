<?php
session_start();
require_once 'controllers/UserController.php';
require_once 'controllers/BookController.php';

$action = isset($_GET['action']) ? $_GET['action'] : 'login'; // Default to login

$db = new PDO('mysql:host=localhost;dbname=library_db', 'root', '');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$userController = new UserController($db);
$bookController = new BookController($db);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UOVT Library System</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <?php
    try {
        ob_start();
        echo "<!-- Debug: Action is $action, Method: {$_SERVER['REQUEST_METHOD']} -->";
        switch ($action) {
            case 'register':
                $userController->register();
                break;
            case 'login':
                $userController->login();
                break;
            case 'logout':
                $userController->logout();
                break;
            case 'dashboard':
                $bookController->dashboard();
                break;
            case 'books':
                $bookController->index();
                break;
            case 'addBook':
                $bookController->addBookForm();
                break;
            case 'saveBook':
                $bookController->saveBook($_POST);
                break;
            case 'editBook':
                $bookController->editBookForm($_GET['id']);
                break;
            case 'updateBook':
                $bookController->updateBook($_POST);
                break;
            case 'deleteBook':
                $bookController->deleteBook($_GET['id']);
                break;
            case 'searchBooks':
                $bookController->searchBooks($_GET['query']);
                break;
            case 'issueBook':
                $bookController->issueBook($_POST);
                break;
            case 'returnBook':
                $bookController->returnBook($_GET['id']);
                break;
            case 'issuedBooks':
                $bookController->issuedBooks();
                break;
            case 'attendance':
                $userController->attendance();
                break;
            default:
                $userController->login(); // Fallback to login
                break;
        }
        $content = ob_get_clean();
        if (!headers_sent()) {
            echo '<div class="container">' . $content . '</div>';
        } else {
            echo "<!-- Headers already sent, content not displayed -->";
        }
    } catch (PDOException $e) {
        echo '<div class="container"><h2>Error</h2><p>Database error: ' . htmlspecialchars($e->getMessage()) . '</p></div>';
    }
    ?>
</body>
</html>