<?php
// Include database pdoection and other common functions
include 'conn.php'; // This should contain your database pdoection code

session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id']; // Assuming user_id is stored in the session

function getCompletedCycles($pdo, $user_id) {
    $stmt = $pdo->prepare("SELECT * FROM period_cycles WHERE user_id = :user_id AND status = 'Completed' ORDER BY start_date DESC");
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC); // Fetch as associative array
}

function calculateAverageCycleLength($completed_cycles) {
    $total_days = 0;
    $cycle_count = count($completed_cycles);

    foreach ($completed_cycles as $cycle) {
        $start_date = new DateTime($cycle['start_date']);
        $end_date = new DateTime($cycle['end_date']);
        $interval = $start_date->diff($end_date);
        $total_days += $interval->days;
    }

    return $cycle_count > 0 ? round($total_days / $cycle_count) : 0; // Return average length
}


function estimateNextPeriod($completed_cycles, $average_cycle_length) {
    if (count($completed_cycles) == 0) {
        return null; // No completed cycles, cannot estimate
    }

    $last_cycle = $completed_cycles[0]; // The most recent cycle
    $last_end_date = new DateTime($last_cycle['end_date']);
    $next_period = $last_end_date->add(new DateInterval("P{$average_cycle_length}D"));

    return $next_period->format('Y-m-d');
}

// Function to fetch the user's period cycles using PDO
function getPeriodCycles($pdo, $user_id) {
    $stmt = $pdo->prepare("SELECT * FROM period_cycles WHERE user_id = :user_id");
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC); // Fetch as associative array
}

// Function to add a new period cycle using PDO
function addNewCycle($pdo, $user_id) {
    $stmt = $pdo->prepare("INSERT INTO period_cycles (user_id, start_date) VALUES (:user_id, CURDATE())");
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
}

// Function to update the end date of a period cycle using PDO
function updateEndDate($pdo, $cycle_id) {
    $stmt = $pdo->prepare("UPDATE period_cycles SET end_date = CURDATE(), status = 'Completed' WHERE id = :cycle_id AND end_date IS NULL");
    $stmt->bindParam(':cycle_id', $cycle_id, PDO::PARAM_INT);
    $stmt->execute();
}

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_cycle'])) {
        // Add a new cycle
        addNewCycle($pdo, $user_id);
    } elseif (isset($_POST['update_cycle'])) {
        // Update the end date of the selected cycle
        $cycle_id = $_POST['cycle_id'];
        updateEndDate($pdo, $cycle_id);
    }
}

// Fetch the user's period cycles
$cycles = getPeriodCycles($pdo, $user_id);

$completed_cycles = getCompletedCycles($pdo, $user_id);

// Calculate average cycle length
$average_cycle_length = calculateAverageCycleLength($completed_cycles);

// Estimate the next period
$next_period = estimateNextPeriod($completed_cycles, $average_cycle_length);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Period Cycle Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">

<div class="container mx-auto p-6">
    <h1 class="text-3xl font-bold mb-6">Period Cycle Dashboard</h1>

    <!-- Add New Cycle Button -->
    <form method="POST" action="">
        <button  id="openModalButton" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-700">Add New Cycle</button>
    </form>
    
    <!-- Confirmation Modal -->
<div id="confirmationModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden">
    <div class="bg-white p-6 rounded-lg shadow-lg">
        <h2 class="text-xl font-bold mb-4">Are you sure you have your period today?</h2>
        <p class="mb-4">One false step, and we can't calculate your periods accurately.</p>
        <div class="flex justify-end">
            <button id="cancelButton" class="bg-gray-500 text-white px-4 py-2 rounded-lg mr-2 hover:bg-gray-700">Cancel</button>
            <form method="POST" action="">
                <button type="submit" name="add_cycle" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-700">Confirm</button>
            </form>
        </div>
    </div>
</div>


    <!-- Display Next Period Estimation -->
    <?php if ($next_period): ?>
        <div class="mt-6 p-4 bg-green-100 rounded-lg">
            <h2 class="text-xl font-semibold">Estimated Next Period Start Date: <?php echo $next_period; ?></h2>
            <p>Based on an average cycle length of <?php echo $average_cycle_length; ?> days.</p>
        </div>
    <?php else: ?>
        <div class="mt-6 p-4 bg-red-100 rounded-lg">
            <h2 class="text-xl font-semibold">Insufficient data to estimate the next period.</h2>
        </div>
    <?php endif; ?>


    <!-- Period Cycles as Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mt-6">
        <?php foreach ($cycles as $cycle): ?>
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h2 class="text-xl font-semibold mb-2">Cycle ID: <?php echo $cycle['id']; ?></h2>
                <p><strong>Start Date:</strong> <?php echo $cycle['start_date']; ?></p>
                <p><strong>End Date:</strong> <?php echo $cycle['end_date'] ? $cycle['end_date'] : 'Ongoing'; ?></p>
                <p><strong>Status:</strong> <?php echo $cycle['status']; ?></p>

                <!-- Show button to complete cycle if it's still pending -->
                <?php if ($cycle['status'] == 'Pending'): ?>
                    <form method="POST" action="">
                        <input type="hidden" name="cycle_id" value="<?php echo $cycle['id']; ?>">
                        <button type="submit" name="update_cycle" class="bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-700 mt-4">Complete Cycle</button>
                    </form>
                <?php else: ?>
                    <span class="text-gray-500">Completed</span>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<script>
    // Get modal elements
    const modal = document.getElementById('confirmationModal');
    const openModalButton = document.getElementById('openModalButton');
    const cancelButton = document.getElementById('cancelButton');

    // Show the modal when the "Add New Cycle" button is clicked
    openModalButton.addEventListener('click', function() {
        modal.classList.remove('hidden');
    });

    // Hide the modal when the "Cancel" button is clicked
    cancelButton.addEventListener('click', function() {
        modal.classList.add('hidden');
    });

    // Close modal if user clicks outside of the modal content
    window.addEventListener('click', function(event) {
        if (event.target == modal) {
            modal.classList.add('hidden');
        }
    });
</script>


</body>
</html>
