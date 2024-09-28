<?php
require_once 'config.php';

if (isset($_POST['subject_id'])) {
    $subject_id = $_POST['subject_id'];
    $selected_chapter = $_POST['selected_chapter'] ?? null;

    $stmt = $pdo->prepare("SELECT * FROM chapters WHERE subject_id = ?");
    $stmt->execute([$subject_id]);
    $chapters = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo '<option value="">Select Chapter</option>';
    foreach ($chapters as $chapter) {
        $selected = ($selected_chapter == $chapter['id']) ? 'selected' : '';
        echo '<option value="' . $chapter['id'] . '" ' . $selected . '>' . htmlspecialchars($chapter['chapter_name']) . '</option>';
    }
}
?>