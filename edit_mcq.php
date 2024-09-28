<?php
require_once 'config.php';

$id = $_GET['id'];

// Fetch the MCQ to edit
$mcq = $pdo->prepare("SELECT * FROM mcqs WHERE id = ?");
$mcq->execute([$id]);
$mcq = $mcq->fetch(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $question = $_POST['question'];
    $option_a = $_POST['option_a'];
    $option_b = $_POST['option_b'];
    $option_c = $_POST['option_c'];
    $option_d = $_POST['option_d'];
    $correct_answer = $_POST['correct_answer'];

    // Update the MCQ
    $stmt = $pdo->prepare("UPDATE mcqs SET question = ?, option_a = ?, option_b = ?, option_c = ?, option_d = ?, correct_answer = ? WHERE id = ?");
    $stmt->execute([$question, $option_a, $option_b, $option_c, $option_d, $correct_answer, $id]);

    header('Location: mcqs.php');
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit MCQ</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <?php include 'navbar.php'; ?>

    <div class="container mt-4">
        <h1>Edit MCQ</h1>
        <form method="POST">
            <div class="mb-3">
                <label for="question" class="form-label">Question</label>
                <textarea class="form-control" id="question" name="question" required><?php echo $mcq['question']; ?></textarea>
            </div>
            <div class="mb-3">
                <label for="option_a" class="form-label">Option A</label>
                <input type="text" class="form-control" id="option_a" name="option_a" value="<?php echo $mcq['option_a']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="option_b" class="form-label">Option B</label>
                <input type="text" class="form-control" id="option_b" name="option_b" value="<?php echo $mcq['option_b']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="option_c" class="form-label">Option C</label>
                <input type="text" class="form-control" id="option_c" name="option_c" value="<?php echo $mcq['option_c']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="option_d" class="form-label">Option D</label>
                <input type="text" class="form-control" id="option_d" name="option_d" value="<?php echo $mcq['option_d']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="correct_answer" class="form-label">Correct Answer</label>
                <select class="form-select" id="correct_answer" name="correct_answer" required>
                    <option value="A" <?php echo ($mcq['correct_answer'] == 'A') ? 'selected' : ''; ?>>A</option>
                    <option value="B" <?php echo ($mcq['correct_answer'] == 'B') ? 'selected' : ''; ?>>B</option>
                    <option value="C" <?php echo ($mcq['correct_answer'] == 'C') ? 'selected' : ''; ?>>C</option>
                    <option value="D" <?php echo ($mcq['correct_answer'] == 'D') ? 'selected' : ''; ?>>D</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Update MCQ</button>
        </form>
    </div>
</body>
</html>
