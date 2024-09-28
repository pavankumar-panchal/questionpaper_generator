<?php
require_once 'config.php';

$classes = $pdo->query("SELECT * FROM classes")->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $topic_id = $_POST['topic_id'];
    $questions = $_POST['question'];
    $options_a = $_POST['option_a'];
    $options_b = $_POST['option_b'];
    $options_c = $_POST['option_c'];
    $options_d = $_POST['option_d'];
    $correct_answers = $_POST['correct_answer'];

    foreach ($questions as $index => $question) {
        $stmt = $pdo->prepare("INSERT INTO mcqs (question, option_a, option_b, option_c, option_d, correct_answer, topic_id) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $question,
            $options_a[$index],
            $options_b[$index],
            $options_c[$index],
            $options_d[$index],
            $correct_answers[$index],
            $topic_id
        ]);
    }

    $success_message = count($questions) . " MCQs added successfully!";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Multiple MCQs</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
    <?php include 'navbar.php'; ?>

    <div class="container mt-4">
        <h1>Add Multiple MCQs</h1>
        <?php if (isset($success_message)): ?>
            <div class="alert alert-success" role="alert">
                <?php echo $success_message; ?>
            </div>
        <?php endif; ?>
        <form method="POST" action="">
            <div class="mb-3">
                <label for="class_id" class="form-label">Class</label>
                <select class="form-select" id="class_id" name="class_id" required>
                    <option value="">Select Class</option>
                    <?php foreach ($classes as $class): ?>
                        <option value="<?php echo $class['id']; ?>"><?php echo $class['class_name']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="subject_id" class="form-label">Subject</label>
                <select class="form-select" id="subject_id" name="subject_id" required disabled>
                    <option value="">Select Subject</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="chapter_id" class="form-label">Chapter</label>
                <select class="form-select" id="chapter_id" name="chapter_id" required disabled>
                    <option value="">Select Chapter</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="topic_id" class="form-label">Topic</label>
                <select class="form-select" id="topic_id" name="topic_id" required disabled>
                    <option value="">Select Topic</option>
                </select>
            </div>

            <div id="mcq-forms-container">
                <!-- First MCQ form (Default) -->
                <div class="mcq-form mb-4">
                    <h4>MCQ 1</h4>
                    <div class="mb-3">
                        <label for="question_1" class="form-label">Question</label>
                        <textarea class="form-control" id="question_1" name="question[]" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="option_a_1" class="form-label">Option A</label>
                        <input type="text" class="form-control" id="option_a_1" name="option_a[]" required>
                    </div>
                    <div class="mb-3">
                        <label for="option_b_1" class="form-label">Option B</label>
                        <input type="text" class="form-control" id="option_b_1" name="option_b[]" required>
                    </div>
                    <div class="mb-3">
                        <label for="option_c_1" class="form-label">Option C</label>
                        <input type="text" class="form-control" id="option_c_1" name="option_c[]" required>
                    </div>
                    <div class="mb-3">
                        <label for="option_d_1" class="form-label">Option D</label>
                        <input type="text" class="form-control" id="option_d_1" name="option_d[]" required>
                    </div>
                    <div class="mb-3">
                        <label for="correct_answer_1" class="form-label">Correct Answer</label>
                        <select class="form-select" id="correct_answer_1" name="correct_answer[]" required>
                            <option value="A">A</option>
                            <option value="B">B</option>
                            <option value="C">C</option>
                            <option value="D">D</option>
                        </select>
                    </div>
                </div>
            </div>

            <button type="button" class="btn btn-secondary" id="add-mcq-btn">Add Another MCQ</button>
            <button type="submit" class="btn btn-primary mt-3">Add MCQs</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>



    <script>
        $(document).ready(function () {
            let mcqCount = 1; // Keep track of how many MCQs are added

            // Function to renumber MCQs after adding/removing one
            function renumberMCQs() {
                $('.mcq-form').each(function (index) {
                    // Update the MCQ title (e.g., MCQ 1, MCQ 2, etc.)
                    $(this).find('h4').text(`MCQ ${index + 1}`);
                    // Update the IDs and "for" attributes of the inputs inside the MCQ form
                    $(this).find('label[for^="question_"]').attr('for', `question_${index + 1}`);
                    $(this).find('textarea[id^="question_"]').attr('id', `question_${index + 1}`);
                    $(this).find('label[for^="option_a_"]').attr('for', `option_a_${index + 1}`);
                    $(this).find('input[id^="option_a_"]').attr('id', `option_a_${index + 1}`);
                    $(this).find('label[for^="option_b_"]').attr('for', `option_b_${index + 1}`);
                    $(this).find('input[id^="option_b_"]').attr('id', `option_b_${index + 1}`);
                    $(this).find('label[for^="option_c_"]').attr('for', `option_c_${index + 1}`);
                    $(this).find('input[id^="option_c_"]').attr('id', `option_c_${index + 1}`);
                    $(this).find('label[for^="option_d_"]').attr('for', `option_d_${index + 1}`);
                    $(this).find('input[id^="option_d_"]').attr('id', `option_d_${index + 1}`);
                    $(this).find('label[for^="correct_answer_"]').attr('for', `correct_answer_${index + 1}`);
                    $(this).find('select[id^="correct_answer_"]').attr('id', `correct_answer_${index + 1}`);
                });
            }

            // Add another MCQ form when "Add Another MCQ" is clicked
            $('#add-mcq-btn').click(function () {
                mcqCount++;
                const mcqForm = `
                <div class="mcq-form mb-4">
                    <h4>MCQ ${mcqCount}</h4>
                    <button type="button" class="btn btn-danger btn-sm mb-3 remove-mcq-btn">Remove MCQ</button>
                    <div class="mb-3">
                        <label for="question_${mcqCount}" class="form-label">Question</label>
                        <textarea class="form-control" id="question_${mcqCount}" name="question[]" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="option_a_${mcqCount}" class="form-label">Option A</label>
                        <input type="text" class="form-control" id="option_a_${mcqCount}" name="option_a[]" required>
                    </div>
                    <div class="mb-3">
                        <label for="option_b_${mcqCount}" class="form-label">Option B</label>
                        <input type="text" class="form-control" id="option_b_${mcqCount}" name="option_b[]" required>
                    </div>
                    <div class="mb-3">
                        <label for="option_c_${mcqCount}" class="form-label">Option C</label>
                        <input type="text" class="form-control" id="option_c_${mcqCount}" name="option_c[]" required>
                    </div>
                    <div class="mb-3">
                        <label for="option_d_${mcqCount}" class="form-label">Option D</label>
                        <input type="text" class="form-control" id="option_d_${mcqCount}" name="option_d[]" required>
                    </div>
                    <div class="mb-3">
                        <label for="correct_answer_${mcqCount}" class="form-label">Correct Answer</label>
                        <select class="form-select" id="correct_answer_${mcqCount}" name="correct_answer[]" required>
                            <option value="A">A</option>
                            <option value="B">B</option>
                            <option value="C">C</option>
                            <option value="D">D</option>
                        </select>
                    </div>
                </div>`;

                $('#mcq-forms-container').append(mcqForm);
            });

            // Remove an MCQ form and renumber the remaining MCQs
            $(document).on('click', '.remove-mcq-btn', function () {
                $(this).closest('.mcq-form').remove(); // Remove the specific MCQ form
                renumberMCQs(); // Renumber the remaining MCQs
            });

            // Fetch subjects, chapters, and topics (same as before)
            $('#class_id').change(function () {
                var classId = $(this).val();
                if (classId) {
                    $.ajax({
                        url: 'get_subjects.php',
                        type: 'POST',
                        data: { class_id: classId },
                        success: function (html) {
                            $('#subject_id').html(html).prop('disabled', false);
                            $('#chapter_id').html('<option value="">Select Chapter</option>').prop('disabled', true);
                            $('#topic_id').html('<option value="">Select Topic</option>').prop('disabled', true);
                        }
                    });
                }
            });

            $('#subject_id').change(function () {
                var subjectId = $(this).val();
                if (subjectId) {
                    $.ajax({
                        url: 'get_chapters.php',
                        type: 'POST',
                        data: { subject_id: subjectId },
                        success: function (html) {
                            $('#chapter_id').html(html).prop('disabled', false);
                            $('#topic_id').html('<option value="">Select Topic</option>').prop('disabled', true);
                        }
                    });
                }
            });

            $('#chapter_id').change(function () {
                var chapterId = $(this).val();
                if (chapterId) {
                    $.ajax({
                        url: 'get_topics.php',
                        type: 'POST',
                        data: { chapter_id: chapterId },
                        success: function (html) {
                            $('#topic_id').html(html).prop('disabled', false);
                        }
                    });
                }
            });
        });
    </script>

</body>

</html>