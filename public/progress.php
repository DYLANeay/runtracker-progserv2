<?php
/**
 * Progress Page
 *
 * Displays all recorded runs for the authenticated user in reverse chronological order.
 * Shows date, distance, duration, pace, and notes for each run.
 *
 * @uses $_SESSION['user_id'] User ID for retrieving user's runs
 * @uses $_SESSION['username'] Username for display
 *
 * Security: Requires authenticated user session
 * Access: Authenticated users only (can only view their own runs)
 */

session_start();

/**
 * Check if user is authenticated
 * Redirects to login page if no valid session exists
 */
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

require __DIR__ . "/../src/utils/autoloader.php";
require __DIR__ . "/../src/i18n/Language.php";

use RunTracker\Database\Database;
use RunTracker\I18n\Language;
use function RunTracker\I18n\t;
use function RunTracker\I18n\currentLang;

/** @var Language $lang Language instance for translations */
$lang = Language::getInstance();

/** @var string $username Current logged-in user's username */
$username = $_SESSION["username"] ?? "Utilisateur";

/** @var int $user_id Current logged-in user's ID */
$user_id = $_SESSION["user_id"];

/** @var array $runs Array of run records from database */
$runs = [];

/** @var string $error_message Error message if database query fails */
$error_message = "";

/**
 * Retrieve all runs for the current user from database
 * Ordered by date in descending order (most recent first)
 */
try {
    $db = new Database();
    $pdo = $db->getPdo();

    /**
     * Query to fetch all runs for the authenticated user
     * Includes: id, date, distance, duration, pace, notes
     * Sorted by date (newest first)
     */
    $stmt = $pdo->prepare('
        SELECT
            id, date, distance, duration, pace, notes
        FROM
            runs
        WHERE
            user_id = :user_id
        ORDER BY
            date DESC
    ');

    $stmt->execute(["user_id" => $user_id]);

    /**
     * Fetch all runs as associative array
     */
    $runs = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    /**
     * Handle database errors gracefully
     */
    $error_message = t("progress_error_fetch");
}
?>

<!DOCTYPE html>
<html lang="<?= currentLang() ?>">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="color-scheme" content="light dark">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@picocss/pico@2/css/pico.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <script src="https://cdn.jsdelivr.net/npm/echarts@5/dist/echarts.min.js"></script>

    <title><?= t("progress_title") ?> | <?= t("app_name") ?></title>
</head>

<body>
    <header class="container">
        <nav>
            <ul>
                <li><strong><?= t("app_name") ?></strong></li>
            </ul>
            <ul>
                <li><?= t("welcome_user") ?> <strong><?= htmlspecialchars(
     $username,
 ) ?></strong> !</li>
                <li><a href="./logout.php" role="button" class="secondary"><?= t(
                    "logout",
                ) ?></a></li>
            </ul>
        </nav>
    </header>

    <main class="container">
        <h1><?= t("progress_title") ?></h1>

        <?php if (!empty($error_message)): ?>
            <article class="error" style="background-color: #f8d7da; color: #721c24; padding: 1rem; border-radius: 5px;">
                <?= $error_message ?>
            </article>
        <?php endif; ?>

        <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1rem;">
            <h2 style="margin: 0;"><?= t("progress_your_runs") ?></h2>
            <?php if (!empty($runs)): ?>
            <button id="toggleChartBtn" type="button" class="secondary" style="margin: 0;">
                <?= t("progress_show_chart") ?>
            </button>
            <?php endif; ?>
        </div>

        <div id="chartContainer" style="display: none; margin-bottom: 2rem;">
            <div id="progressChart" style="width: 100%; height: 400px;"></div>
        </div>

        <?php if (empty($runs)): ?>
            <p><?= t("progress_no_runs") ?> <a href="./create.php"><?= t(
     "progress_first_run",
 ) ?></a></p>
        <?php else: ?>

            <figure>
            <table role="grid">
      <thead>
    <tr>
        <th><?= t("progress_date") ?></th>
        <th><?= t("progress_distance") ?></th>
        <th><?= t("progress_duration") ?></th>
        <th><?= t("progress_pace") ?></th>
        <th><?= t("progress_notes") ?></th>
        <th>Actions</th> </tr>
</thead>
       <tbody>
    <?php foreach ($runs as $run): ?>
        <tr>
            <td><?= htmlspecialchars($run["date"]) ?></td>
            <td><?= htmlspecialchars($run["distance"]) ?></td>
            <td><?= htmlspecialchars($run["duration"]) ?></td>
            <td><?= htmlspecialchars($run["pace"]) ?></td>
            <td><?= htmlspecialchars(
                substr($run["notes"], 0, 50),
            ) .
                (strlen($run["notes"]) > 50
                    ? "..."
                    : "") ?></td>
            
            <td>
                <form action="delete_run.php" method="POST" onsubmit="return confirm('<?= t('progress_confirm_delete') ?>');" style="margin: 0;">
                    <input type="hidden" name="run_id" value="<?= $run['id'] ?>">
                    <button type="submit" class="outline contrast" style="padding: 0.2rem 0.5rem; font-size: 0.8rem; border-color: #d93526; color: #d93526;">
                        X
                    </button>
                </form>
            </td>
            
        </tr>
    <?php endforeach; ?>
</tbody>
            </table>
            </figure>

        <?php endif; ?>

        <br>
        <button><a href="./index.php" role="button" class="secondary"><?= t(
            "progress_back_dashboard",
        ) ?></a></button>
        <button><a href="./create.php" role="button" class="secondary"><?= t(
            "progress_add_run",
        ) ?></a></button>

    </main>

    <?php include __DIR__ . "/../src/i18n/language-footer.php"; ?>

    <?php if (!empty($runs)): ?>
    <script>
        (function() {
            const toggleBtn = document.getElementById('toggleChartBtn');
            const chartContainer = document.getElementById('chartContainer');
            const tableContainer = document.querySelector('figure');
            let chart = null;
            let chartVisible = false;

            // Translations
            const translations = {
                chartTitle: <?= json_encode(t("progress_chart_title")) ?>,
                distance: <?= json_encode(t("progress_chart_distance")) ?>,
                duration: <?= json_encode(t("progress_chart_duration")) ?>,
                pace: <?= json_encode(t("progress_chart_pace")) ?>,
                leftAxis: <?= json_encode(t("progress_chart_left_axis")) ?>,
                showChart: <?= json_encode(t("progress_show_chart")) ?>,
                showTable: <?= json_encode(t("progress_show_table")) ?>
            };

            const runsData = <?= json_encode(array_reverse($runs)) ?>;

            const dates = runsData.map(run => run.date);
            const distances = runsData.map(run => parseFloat(run.distance));

            // Convert duration
            const durations = runsData.map(run => {
                const parts = run.duration.split(':');
                return parseInt(parts[0]) * 60 + parseInt(parts[1]) + parseInt(parts[2]) / 60;
            });

            // Convert pace
            const paces = runsData.map(run => {
                const parts = run.pace.split(':');
                return parseInt(parts[0]) + parseInt(parts[1]) / 60;
            });

            function initChart() {
                chart = echarts.init(document.getElementById('progressChart'));

                const option = {
                    title: {
                        text: translations.chartTitle,
                        left: 'center'
                    },
                    tooltip: {
                        trigger: 'axis',
                        axisPointer: {
                            type: 'cross'
                        }
                    },
                    legend: {
                        data: [translations.distance, translations.duration, translations.pace],
                        top: 30
                    },
                    grid: {
                        left: '3%',
                        right: '4%',
                        bottom: '3%',
                        top: 80,
                        containLabel: true
                    },
                    xAxis: {
                        type: 'category',
                        boundaryGap: false,
                        data: dates,
                        axisLabel: {
                            rotate: 45
                        }
                    },
                    yAxis: [
                        {
                            type: 'value',
                            name: translations.leftAxis,
                            position: 'left'
                        },
                        {
                            type: 'value',
                            name: translations.pace,
                            position: 'right',
                            inverse: true
                        }
                    ],
                    series: [
                        {
                            name: translations.distance,
                            type: 'line',
                            data: distances,
                            smooth: true,
                            itemStyle: { color: '#5470c6' }
                        },
                        {
                            name: translations.duration,
                            type: 'line',
                            data: durations,
                            smooth: true,
                            itemStyle: { color: '#91cc75' }
                        },
                        {
                            name: translations.pace,
                            type: 'line',
                            yAxisIndex: 1,
                            data: paces,
                            smooth: true,
                            itemStyle: { color: '#ee6666' }
                        }
                    ]
                };

                chart.setOption(option);

                window.addEventListener('resize', function() {
                    if (chart) {
                        chart.resize();
                    }
                });
            }

            toggleBtn.addEventListener('click', function() {
                chartVisible = !chartVisible;

                if (chartVisible) {
                    chartContainer.style.display = 'block';
                    tableContainer.style.display = 'none';
                    toggleBtn.textContent = translations.showTable;

                    if (!chart) {
                        initChart();
                    } else {
                        chart.resize();
                    }
                } else {
                    chartContainer.style.display = 'none';
                    tableContainer.style.display = 'block';
                    toggleBtn.textContent = translations.showChart;
                }
            });
        })();
    </script>
    <?php endif; ?>
</body>

</html>
