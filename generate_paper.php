<?php
require_once 'config.php';

// Fetch topics for dropdown
$stmt = $pdo->query("SELECT topics.id, topics.topic_name, chapters.chapter_name, subjects.subject_name, classes.class_name 
                     FROM topics 
                     JOIN chapters ON topics.chapter_id = chapters.id 
                     JOIN subjects ON chapters.subject_id = subjects.id 
                     JOIN classes ON subjects.class_id = classes.id");
$topics = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $selected_topics = isset($_POST['topics']) ? $_POST['topics'] : [];
    $num_questions = isset($_POST['num_questions']) ? intval($_POST['num_questions']) : 0;

    // Fetch MCQs for selected topics
    if (!empty($selected_topics) && $num_questions > 0) {
        $placeholders = implode(',', array_fill(0, count($selected_topics), '?'));
        $sql = "SELECT * FROM mcqs WHERE topic_id IN ($placeholders) ORDER BY RAND() LIMIT ?";
        $stmt = $pdo->prepare($sql);
        
        // Combine selected topics and num_questions into a single array
        $params = array_merge($selected_topics, [$num_questions]);
        
        $stmt->execute($params);
        $mcqs = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Generate question paper
        $question_paper = "<h1>Generated Question Paper</h1>";
        foreach ($mcqs as $index => $mcq) {
            $question_paper .= "<p><strong>Q" . ($index + 1) . ". " . htmlspecialchars($mcq['question']) . "</strong></p>";
            $question_paper .= "<p>A. " . htmlspecialchars($mcq['option_a']) . "</p>";
            $question_paper .= "<p>B. " . htmlspecialchars($mcq['option_b']) . "</p>";
            $question_paper .= "<p>C. " . htmlspecialchars($mcq['option_c']) . "</p>";
            $question_paper .= "<p>D. " . htmlspecialchars($mcq['option_d']) . "</p>";
        }
    } else {
        $error_message = "Please select at least one topic and enter a valid number of questions.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generate Question Paper</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php include 'navbar.php'; ?>

    <div class="container mt-4">
        <h1>Generate Question Paper</h1>
        <?php if (isset($error_message)): ?>
            <div class="alert alert-danger" role="alert">
                <?php echo $error_message; ?>
            </div>
        <?php endif; ?>
        <form method="POST" action="">
            <div class="mb-3">
                <label for="topics" class="form-label">Select Topics</label>
                <select class="form-select" id="topics" name="topics[]" multiple required>
                    <?php foreach ($topics as $topic): ?>
                        <option value="<?php echo $topic['id']; ?>">
                            <?php echo "{$topic['class_name']} - {$topic['subject_name']} - {$topic['chapter_name']} - {$topic['topic_name']}"; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="num_questions" class="form-label">Number of Questions</label>
                <input type="number" class="form-control" id="num_questions" name="num_questions" required min="1">
            </div>
            <button type="submit" class="btn btn-primary">Generate Paper</button>
        </form>

        <?php if (isset($question_paper)): ?>
            <div class="mt-4">
                <?php echo $question_paper; ?>
            </div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>