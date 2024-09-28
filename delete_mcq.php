<?php
require_once 'config.php';

$id = $_GET['id'];

// Delete the MCQ
$stmt = $pdo->prepare("DELETE FROM mcqs WHERE id = ?");
$stmt->execute([$id]);

header('Location: mcqs.php');
exit;
?>
