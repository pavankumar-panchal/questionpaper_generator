<?php
require_once 'config.php';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $question = $_POST['question'];
    $option_a = $_POST['option_a'];
    $option_b = $_POST['option_b'];
    $option_c = $_POST['option_c'];
    $option_d = $_POST['option_d'];
    $correct_answer = $_POST['correct_answer'];
    $topic_id = $_POST['topic_id'];

    $stmt = $pdo->prepare("INSERT INTO mcqs (question, option_a, option_b, option_c, option_d, correct_answer, topic_id) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$question, $option_a, $option_b, $option_c, $option_d, $correct_answer, $topic_id]);
}

// Fetch topics for dropdown
$stmt = $pdo->query("SELECT topics.id, topics.topic_name, chapters.chapter_name, subjects.subject_name, classes.class_name 
                     FROM topics 
                     JOIN chapters ON topics.chapter_id = chapters.id 
                     JOIN subjects ON chapters.subject_id = subjects.id 
                     JOIN classes ON subjects.class_id = classes.id");
$topics = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
        <form method="POST" action="">
            <div class="mb-3">
                <label for="topic_id" class="form-label">Topic</label>
                <select class="form-select" id="topic_id" name="topic_id" required>
                    <?php foreach ($topics as $topic): ?>
                        <option value="<?php echo $topic['id']; ?>">
                            <?php echo "{$topic['class_name']} - {$topic['subject_name']} - {$topic['chapter_name']} - {$topic['topic_name']}"; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="question" class="form-label">Question</label>
                <textarea class="form-control" id="question" name="question" required></textarea>
            </div>
            <div class="mb-3">
                <label for="option_a" class="form-label">Option A</label>
                <input type="text" class="form-control" id="option_a" name="option_a" required>
            </div>
            <div class="mb-3">
                <label for="option_b" class="form-label">Option B</label>
                <input type="text" class="form-control" id="option_b" name="option_b" required>
            </div>
            <div class="mb-3">
                <label for="option_c" class="form-label">Option C</label>
                <input type="text" class="form-control" id="option_c" name="option_c" required>
            </div>
            <div class="mb-3">
                <label for="option_d" class="form-label">Option D</label>
                <input type="text" class="form-control" id="option_d" name="option_d" required>
            </div>
            <div class="mb-3">
                <label for="correct_answer" class="form-label">Correct Answer</label>
                <select class="form-select" id="correct_answer" name="correct_answer" required>
                    <option value="A">A</option>
                    <option value="B">B</option>
                    <option value="C">C</option>
                    <option value="D">D</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Add MCQ</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>