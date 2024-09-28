<?php
require_once 'config.php';

if (isset($_POST['class_id'])) {
    $class_id = $_POST['class_id'];
    $selected_subject = $_POST['selected_subject'] ?? null;

    $stmt = $pdo->prepare("SELECT * FROM subjects WHERE class_id = ?");
    $stmt->execute([$class_id]);
    $subjects = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo '<option value="">Select Subject</option>';
    foreach ($subjects as $subject) {
        $selected = ($selected_subject == $subject['id']) ? 'selected' : '';
        echo '<option value="' . $subject['id'] . '" ' . $selected . '>' . htmlspecialchars($subject['subject_name']) . '</option>';
    }
}
?>