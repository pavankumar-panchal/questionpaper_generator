<?php
require_once 'config.php';

$classes = $pdo->query("SELECT * FROM classes")->fetchAll(PDO::FETCH_ASSOC);

$selected_class = $_POST['class_id'] ?? null;
$selected_subject = $_POST['subject_id'] ?? null;
$selected_chapter = $_POST['chapter_id'] ?? null;
$selected_topic = $_POST['topic_id'] ?? null;

$mcqs = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST' && $selected_class) {
    $where_conditions = ["classes.id = ?"];
    $params = [$selected_class];

    if ($selected_subject) {
        $where_conditions[] = "subjects.id = ?";
        $params[] = $selected_subject;
    }
    if ($selected_chapter) {
        $where_conditions[] = "chapters.id = ?";
        $params[] = $selected_chapter;
    }
    if ($selected_topic) {
        $where_conditions[] = "topics.id = ?";
        $params[] = $selected_topic;
    }

    $where_clause = implode(" AND ", $where_conditions);

    $sql = "SELECT mcqs.*, topics.topic_name, chapters.chapter_name, subjects.subject_name, classes.class_name
            FROM mcqs
            JOIN topics ON mcqs.topic_id = topics.id
            JOIN chapters ON topics.chapter_id = chapters.id
            JOIN subjects ON chapters.subject_id = subjects.id
            JOIN classes ON subjects.class_id = classes.id
            WHERE $where_clause
            ORDER BY mcqs.id";

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $mcqs = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fetch MCQs</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
    <?php include 'navbar.php'; ?>

    <div class="container mt-4">
        <h1>Fetch MCQs</h1>
        <form method="POST" action="">
            <div class="mb-3">
                <label for="class_id" class="form-label">Class</label>
                <select class="form-select" id="class_id" name="class_id" required>
                    <option value="">Select Class</option>
                    <?php foreach ($classes as $class): ?>
                        <option value="<?php echo $class['id']; ?>" <?php echo $selected_class == $class['id'] ? 'selected' : ''; ?>>
                            <?php echo $class['class_name']; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="subject_id" class="form-label">Subject</label>
                <select class="form-select" id="subject_id" name="subject_id">
                    <option value="">Select Subject</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="chapter_id" class="form-label">Chapter</label>
                <select class="form-select" id="chapter_id" name="chapter_id">
                    <option value="">Select Chapter</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="topic_id" class="form-label">Topic</label>
                <select class="form-select" id="topic_id" name="topic_id">
                    <option value="">Select Topic</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Fetch MCQs</button>
        </form>

        <?php if (!empty($mcqs)): ?>
            <h2 class="mt-4">MCQs (Total: <?php echo count($mcqs); ?>)</h2>
            <form id="question-paper-form" action="generate_paper.php" method="POST">
                <div class="row">
                    <?php foreach ($mcqs as $index => $mcq): ?>
                        <div class="col-md-4 mb-4">
                            <div class="card h-100">
                                <div class="card-body">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="selected_mcqs[]"
                                            value="<?php echo $mcq['id']; ?>" id="mcq_<?php echo $mcq['id']; ?>">
                                        <label class="form-check-label" for="mcq_<?php echo $mcq['id']; ?>">
                                            <h5 class="card-title">Question <?php echo $index + 1; ?></h5>
                                            <p class="card-text"><?php echo htmlspecialchars($mcq['question']); ?></p>
                                            <ul class="list-group">
                                                <li class="list-group-item">A. <?php echo htmlspecialchars($mcq['option_a']); ?>
                                                </li>
                                                <li class="list-group-item">B. <?php echo htmlspecialchars($mcq['option_b']); ?>
                                                </li>
                                                <li class="list-group-item">C. <?php echo htmlspecialchars($mcq['option_c']); ?>
                                                </li>
                                                <li class="list-group-item">D. <?php echo htmlspecialchars($mcq['option_d']); ?>
                                                </li>
                                            </ul>
                                            <p class="mt-2"><strong>Correct Answer:</strong>
                                                <?php echo $mcq['correct_answer']; ?></p>
                                            <p><strong>Class:</strong> <?php echo htmlspecialchars($mcq['class_name']); ?></p>
                                            <p><strong>Subject:</strong> <?php echo htmlspecialchars($mcq['subject_name']); ?>
                                            </p>
                                            <p><strong>Chapter:</strong> <?php echo htmlspecialchars($mcq['chapter_name']); ?>
                                            </p>
                                            <p><strong>Topic:</strong> <?php echo htmlspecialchars($mcq['topic_name']); ?></p>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <button type="submit" class="btn btn-primary mt-3">Generate Question Paper</button>
            </form>
        <?php elseif ($_SERVER['REQUEST_METHOD'] == 'POST'): ?>
            <p class="mt-4">No MCQs found for the selected criteria.</p>
        <?php endif; ?>



    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function () {
            function updateDropdown(targetId, url, data) {
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: data,
                    success: function (html) {
                        $('#' + targetId).html(html).prop('disabled', false);
                    }
                });
            }

            $('#class_id').change(function () {
                var classId = $(this).val();
                if (classId) {
                    updateDropdown('subject_id', 'get_subjects.php', { class_id: classId });
                    $('#chapter_id').html('<option value="">Select Chapter</option>').prop('disabled', true);
                    $('#topic_id').html('<option value="">Select Topic</option>').prop('disabled', true);
                } else {
                    $('#subject_id').html('<option value="">Select Subject</option>').prop('disabled', true);
                    $('#chapter_id').html('<option value="">Select Chapter</option>').prop('disabled', true);
                    $('#topic_id').html('<option value="">Select Topic</option>').prop('disabled', true);
                }
            });

            $('#subject_id').change(function () {
                var subjectId = $(this).val();
                if (subjectId) {
                    updateDropdown('chapter_id', 'get_chapters.php', { subject_id: subjectId });
                    $('#topic_id').html('<option value="">Select Topic</option>').prop('disabled', true);
                } else {
                    $('#chapter_id').html('<option value="">Select Chapter</option>').prop('disabled', true);
                    $('#topic_id').html('<option value="">Select Topic</option>').prop('disabled', true);
                }
            });

            $('#chapter_id').change(function () {
                var chapterId = $(this).val();
                if (chapterId) {
                    updateDropdown('topic_id', 'get_topics.php', { chapter_id: chapterId });
                } else {
                    $('#topic_id').html('<option value="">Select Topic</option>').prop('disabled', true);
                }
            });

            // Set initial values if they exist
            <?php if ($selected_class): ?>
                updateDropdown('subject_id', 'get_subjects.php', { class_id: <?php echo $selected_class; ?> });
                <?php if ($selected_subject): ?>
                    updateDropdown('chapter_id', 'get_chapters.php', { subject_id: <?php echo $selected_subject; ?> });
                    <?php if ($selected_chapter): ?>
                        updateDropdown('topic_id', 'get_topics.php', { chapter_id: <?php echo $selected_chapter; ?> });
                    <?php endif; ?>
                <?php endif; ?>
            <?php endif; ?>
        });
    </script>
</body>

</html>