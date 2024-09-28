<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Multiple MCQs</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        /* Custom styles for better layout */
        .mcq-form {
            border: 1px solid #ccc;
            border-radius: 5px;
            padding: 10px;
            /* Reduced padding */
            margin-bottom: 15px;
            /* Reduced margin */
            font-size: 0.9rem;
            /* Slightly smaller font */
            width: 400px;
            /* Set fixed width */
            height: 400px;
            /* Set fixed height */
            overflow: hidden;
            /* Prevent overflow of content */
        }

        .mcq-form .card-body {
            padding: 10px;
            /* Reduced card body padding */
            height: 100%;
            /* Make card body take full height */
        }

        .mcq-form h5 {
            font-size: 1rem;
            /* Smaller title size */
            margin-bottom: 10px;
            /* Spacing below title */
        }

        .form-label {
            font-size: 0.85rem;
            /* Smaller label size */
        }

        .form-control,
        .form-select {
            font-size: 0.85rem;
            /* Smaller input/select size */
        }

        .btn-small {
            font-size: 0.8rem;
            padding: 0.25rem 0.5rem;
        }

        .row.mb-3 {
            display: flex;
            flex-wrap: wrap;
            /* Allow cards to wrap */
            gap: 15px;
            /* Space between cards */
        }

        .card {
            height: 100%;
            /* Ensure card takes full height */
        }
    </style>
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
            <div class="row mb-4 shadow p-3 rounded" style="background-color: white;">
                <div class="col-md-6 mb-3">
                    <label for="class_id" class="form-label">Class</label>
                    <select class="form-select" id="class_id" name="class_id" required>
                        <option value="">Select Class</option>
                        <?php foreach ($classes as $class): ?>
                            <option value="<?php echo $class['id']; ?>"><?php echo $class['class_name']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="subject_id" class="form-label">Subject</label>
                    <select class="form-select" id="subject_id" name="subject_id" required disabled>
                        <option value="">Select Subject</option>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="chapter_id" class="form-label">Chapter</label>
                    <select class="form-select" id="chapter_id" name="chapter_id" required disabled>
                        <option value="">Select Chapter</option>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="topic_id" class="form-label">Topic</label>
                    <select class="form-select" id="topic_id" name="topic_id" required disabled>
                        <option value="">Select Topic</option>
                    </select>
                </div>
            </div>

            <div id="mcq-forms-container" class="row mb-3">
                <!-- First MCQ form (Default) -->
                <div class="mcq-form col-md-4 mb-3">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title">MCQ 1</h5>
                            <div class="mb-2">
                                <label for="question_1" class="form-label">Question</label>
                                <textarea class="form-control form-control-sm" id="question_1" name="question[]"
                                    rows="2" required></textarea>
                            </div>
                            <div class="row mb-2">
                                <div class="col-6 mb-2">
                                    <label for="option_a_1" class="form-label">A</label>
                                    <input type="text" class="form-control form-control-sm" id="option_a_1"
                                        name="option_a[]" required>
                                </div>
                                <div class="col-6 mb-2">
                                    <label for="option_b_1" class="form-label">B</label>
                                    <input type="text" class="form-control form-control-sm" id="option_b_1"
                                        name="option_b[]" required>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-6 mb-2">
                                    <label for="option_c_1" class="form-label">C</label>
                                    <input type="text" class="form-control form-control-sm" id="option_c_1"
                                        name="option_c[]" required>
                                </div>
                                <div class="col-6 mb-2">
                                    <label for="option_d_1" class="form-label">D</label>
                                    <input type="text" class="form-control form-control-sm" id="option_d_1"
                                        name="option_d[]" required>
                                </div>
                            </div>
                            <div class="mb-2">
                                <label for="correct_answer_1" class="form-label">Correct Answer</label>
                                <select class="form-select form-select-sm" id="correct_answer_1" name="correct_answer[]"
                                    required>
                                    <option value="A">A</option>
                                    <option value="B">B</option>
                                    <option value="C">C</option>
                                    <option value="D">D</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <button type="button" class="btn btn-secondary btn-sm" id="add-mcq-btn">Add Another MCQ</button>
            <button type="submit" class="btn btn-primary btn-sm">Add MCQs</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    $(document).ready(function () {
        let mcqCount = 1; // Keep track of how many MCQs are added

        // Function to renumber MCQs after adding/removing one
        function renumberMCQs() {
            $('.mcq-form').each(function (index) {
                $(this).find('.mcq-header h5').text(`MCQ ${index + 1}`); // Update the MCQ title
                // Update the IDs and "for" attributes of the inputs
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
            <div class="mcq-form col-md-4 mb-3">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mcq-header mb-3">
                            <h5 class="card-title mb-0">MCQ ${mcqCount}</h5>
                            <button type="button" class="btn btn-danger btn-sm remove-mcq-btn" title="Remove MCQ">&times;</button>
                        </div>
                        <div class="mb-2">
                            <label for="question_${mcqCount}" class="form-label">Question</label>
                            <textarea class="form-control" id="question_${mcqCount}" name="question[]" rows="2" style="margin-bottom: -5px;" required></textarea>
                        </div>
                        <div class="row mb-2">
                            <div class="col-6 mb-2">
                                <label for="option_a_${mcqCount}" class="form-label">Option A</label>
                                <input type="text" class="form-control" id="option_a_${mcqCount}" name="option_a[]" required>
                            </div>
                            <div class="col-6 mb-2">
                                <label for="option_b_${mcqCount}" class="form-label">Option B</label>
                                <input type="text" class="form-control" id="option_b_${mcqCount}" name="option_b[]" required>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-6 mb-2">
                                <label for="option_c_${mcqCount}" class="form-label">Option C</label>
                                <input type="text" class="form-control" id="option_c_${mcqCount}" name="option_c[]" required>
                            </div>
                            <div class="col-6 mb-2">
                                <label for="option_d_${mcqCount}" class="form-label">Option D</label>
                                <input type="text" class="form-control" id="option_d_${mcqCount}" name="option_d[]" required>
                            </div>
                        </div>
                        <div class="mb-2">
                            <label for="correct_answer_${mcqCount}" class="form-label">Correct Answer</label>
                            <select class="form-select" id="correct_answer_${mcqCount}" name="correct_answer[]" required>
                                <option value="A">A</option>
                                <option value="B">B</option>
                                <option value="C">C</option>
                                <option value="D">D</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>`;

            $('#mcq-forms-container').append(mcqForm);
        });

        // Remove an MCQ form and renumber the remaining MCQs
        $(document).on('click', '.remove-mcq-btn', function () {
            $(this).closest('.mcq-form').remove(); // Remove the specific MCQ form
            renumberMCQs(); // Renumber the remaining MCQs
        });
    });
</script>


</body>

</html>