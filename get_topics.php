<?php
require_once 'config.php';

if (isset($_POST['chapter_id'])) {
    $chapter_id = $_POST['chapter_id'];
    $selected_topic = $_POST['selected_topic'] ?? null;

    $stmt = $pdo->prepare("SELECT * FROM topics WHERE chapter_id = ?");
    $stmt->execute([$chapter_id]);
    $topics = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo '<option value="">Select Topic</option>';
    foreach ($topics as $topic) {
        $selected = ($selected_topic == $topic['id']) ? 'selected' : '';
        echo '<option value="' . $topic['id'] . '" ' . $selected . '>' . htmlspecialchars($topic['topic_name']) . '</option>';
    }
}
?>