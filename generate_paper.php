<?php
require_once 'config.php';

$selected_mcqs = $_POST['selected_mcqs'] ?? [];

if (!empty($selected_mcqs)) {
    // Fetch selected MCQs from the database
    $placeholders = implode(',', array_fill(0, count($selected_mcqs), '?'));
    $sql = "SELECT mcqs.question, mcqs.option_a, mcqs.option_b, mcqs.option_c, mcqs.option_d 
            FROM mcqs
            WHERE mcqs.id IN ($placeholders)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute($selected_mcqs);
    $mcqs = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Question Paper</title>
    <style>
        /* Ensure A4 page size */
        @page {
            size: A4;
            margin: 20mm;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 10px; /* Make text small enough to fit more questions */
            line-height: 1.2;
            margin: 0;
        }

        /* Grid layout with two columns */
        .question-paper {
            display: grid;
            grid-template-columns: 1fr 1fr; /* 2 columns */
            column-gap: 30px; /* Space between columns */
        }

        .column {
            padding-right: 10px;
        }

        .question {
            margin-bottom: 15px; /* Space after each question */
            padding-bottom: 5px; /* Padding at the bottom of each question */
            border-bottom: 1px solid transparent; /* Invisible line for spacing */
        }

        .options {
            display: flex;
            justify-content: space-between;
            margin-top: 5px; /* Space between question and options */
        }

        .options div {
            width: 48%; /* Ensure options fit horizontally in one line */
        }

        /* Adjust row height dynamically to ensure questions fit nicely */
        .column .question {
            height: auto; /* Let questions adjust to their content */
        }

        /* Print-friendly styles */
        @media print {
            .page-break {
                page-break-after: always; /* Ensure page breaks after each set of questions */
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 style="text-align: center;">Question Paper</h1>

        <?php if (!empty($mcqs)): ?>
            <?php
            $questions_per_page = 24; // You can set a limit on how many questions you want to print
            $total_mcqs = count($mcqs);
            $pages = ceil($total_mcqs / $questions_per_page);
            ?>

            <?php for ($page = 0; $page < $pages; $page++): ?>
                <div class="question-paper">
                    <div class="left-column column">
                        <?php
                        // Slice for the first column
                        $start = $page * $questions_per_page;
                        $end = $start + 12; // First half of questions (12)
                        $left_mcqs = array_slice($mcqs, $start, 12);

                        foreach ($left_mcqs as $index => $mcq):
                        ?>
                            <div class="question">
                                <div class="question-number">
                                    <strong>Q<?php echo $start + $index + 1; ?>:</strong>
                                    <?php echo htmlspecialchars($mcq['question']); ?>
                                </div>
                                <div class="options">
                                    <div>A. <?php echo htmlspecialchars($mcq['option_a']); ?></div>
                                    <div>B. <?php echo htmlspecialchars($mcq['option_b']); ?></div>
                                </div>
                                <div class="options">
                                    <div>C. <?php echo htmlspecialchars($mcq['option_c']); ?></div>
                                    <div>D. <?php echo htmlspecialchars($mcq['option_d']); ?></div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <div class="right-column column">
                        <?php
                        // Slice for the second column
                        $right_mcqs = array_slice($mcqs, $end, 12); // Next 12 questions

                        foreach ($right_mcqs as $index => $mcq):
                        ?>
                            <div class="question">
                                <div class="question-number">
                                    <strong>Q<?php echo $end + $index + 1; ?>:</strong>
                                    <?php echo htmlspecialchars($mcq['question']); ?>
                                </div>
                                <div class="options">
                                    <div>A. <?php echo htmlspecialchars($mcq['option_a']); ?></div>
                                    <div>B. <?php echo htmlspecialchars($mcq['option_b']); ?></div>
                                </div>
                                <div class="options">
                                    <div>C. <?php echo htmlspecialchars($mcq['option_c']); ?></div>
                                    <div>D. <?php echo htmlspecialchars($mcq['option_d']); ?></div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <?php if ($page < $pages - 1): ?>
                    <div class="page-break"></div> <!-- Insert page break after each page -->
                <?php endif; ?>
            <?php endfor; ?>
        <?php else: ?>
            <p>No questions selected.</p>
        <?php endif; ?>
    </div>

    <script>
        window.print(); // Automatically trigger print when the page loads
    </script>
</body>
</html>
