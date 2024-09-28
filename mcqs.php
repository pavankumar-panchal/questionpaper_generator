<?php
require_once 'config.php';

// Fetch all MCQs to display
$mcqs = $pdo->query("SELECT * FROM mcqs")->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage MCQs</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <?php include 'navbar.php'; ?>

    <div class="container mt-4">
        <h1>Manage MCQs</h1>
        <a href="add_mcq.php" class="btn btn-primary mb-3">Add New MCQ</a>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Question</th>
                    <th>Options</th>
                    <th>Correct Answer</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($mcqs as $mcq): ?>
                    <tr>
                        <td><?php echo $mcq['id']; ?></td>
                        <td><?php echo $mcq['question']; ?></td>
                        <td>
                            A: <?php echo $mcq['option_a']; ?><br>
                            B: <?php echo $mcq['option_b']; ?><br>
                            C: <?php echo $mcq['option_c']; ?><br>
                            D: <?php echo $mcq['option_d']; ?>
                        </td>
                        <td><?php echo $mcq['correct_answer']; ?></td>
                        <td>
                            <a href="edit_mcq.php?id=<?php echo $mcq['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                            <a href="delete_mcq.php?id=<?php echo $mcq['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
