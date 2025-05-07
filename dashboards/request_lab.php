<?php
session_start();
require_once '../app/db.php';
include '../includes/header.php';

// Only doctors can access this page
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'doctor') {
    header("Location: ../dashboard/index.php");
    exit;
}

$doctor_id = $_SESSION['doctor_id'];
$patient_id = $_GET['patient_id'] ?? null;
$patientInfo = null;

// Fetch patient info
if ($patient_id) {
    $stmt = $conn->prepare("SELECT full_name, gender, dob, phone, email FROM patients WHERE patients_id = ?");
    $stmt->execute([$patient_id]);
    $patientInfo = $stmt->fetch(PDO::FETCH_ASSOC);
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tests = $_POST['tests'] ?? [];
    $test_cost = 50.00;

    if ($patient_id && !empty($tests)) {
        try {
            $conn->beginTransaction();

            $stmt = $conn->prepare("INSERT INTO lab_requests (patient_id, doctor_id, test_name, status, requested_at, cost) VALUES (?, ?, ?, 'pending', NOW(), ?)");

            foreach ($tests as $test) {
                $stmt->execute([$patient_id, $doctor_id, $test, $test_cost]);
            }

            $conn->commit();
            $_SESSION['message'] = "Lab test(s) successfully requested!";
        } catch (PDOException $e) {
            $conn->rollBack();
            $_SESSION['message'] = "Error: " . $e->getMessage();
        }

        header("Location: request_lab.php?patient_id=" . $patient_id);
        exit;
    } else {
        $_SESSION['message'] = "Please select at least one lab test to request.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Request Lab Test</title>
  <link rel="stylesheet" href="../css/style-pages.css">
  <link rel="stylesheet" href="../css/dashboard-style.css">
  <style>
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background-color: #f5f5f5;
        color: #333;
    }

    .form-container {
        background: #fff;
        padding: 30px;
        margin: 40px auto;
        max-width: 650px;
        border-radius: 10px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.1);
    }

    .form-container h2 {
        color: #2a7da2;
        margin-bottom: 20px;
        font-size: 28px;
        text-align: center;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        font-weight: bold;
        display: block;
        margin-bottom: 8px;
        color: #555;
    }

    select[multiple] {
        width: 100%;
        height: 150px;
        border: 1px solid #ccc;
        border-radius: 8px;
        padding: 12px;
        font-size: 16px;
        color: #333;
        background-color: #f8f8f8;
    }

    .form-actions {
        display: flex;
        justify-content: space-between;
        margin-top: 20px;
    }

    .form-actions button,
    .form-actions .cancel-btn {
        padding: 12px 25px;
        border-radius: 6px;
        font-size: 16px;
        text-align: center;
        width: 48%;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .form-actions button {
        background-color: #2a7da2;
        color: #fff;
        border: none;
    }

    .form-actions button:hover {
        background-color: #1d6a7b;
    }

    .form-actions .cancel-btn {
        background-color: #ccc;
        color: #333;
        text-decoration: none;
        text-align: center;
    }

    .form-actions .cancel-btn:hover {
        background-color: #bbb;
    }

    .alert-message {
        background-color: #dff0d8;
        color: #3c763d;
        padding: 14px;
        border-radius: 6px;
        margin-bottom: 20px;
        border: 1px solid #d6e9c6;
        font-size: 16px;
    }

    .alert-message.error {
        background-color: #f2dede;
        color: #a94442;
        border: 1px solid #ebccd1;
    }

    .alert-message.info {
        background-color: #d9edf7;
        color: #31708f;
        border: 1px solid #bce8f1;
    }

    .form-group small {
        font-size: 14px;
        color: #888;
    }

    /* Media query for smaller screens */
    @media (max-width: 768px) {
        .form-container {
            padding: 20px;
            width: 90%;
        }

        .form-actions button,
        .form-actions .cancel-btn {
            width: 100%;
            margin-bottom: 10px;
        }
    }

    .selected-tests {
        margin-bottom: 15px;
        padding: 10px;
        background-color: #f1f1f1;
        border-radius: 6px;
        border: 1px solid #ccc;
        font-size: 16px;
    }

    .selected-tests span {
        margin-right: 10px;
        background-color: #2a7da2;
        color: #fff;
        padding: 5px 10px;
        border-radius: 20px;
        font-size: 14px;
        cursor: pointer;
    }

    .selected-tests span:hover {
        background-color: #1d6a7b;
    }
</style>
</head>
<body>
<div class="body">
  <?php include '../includes/sidebar.php'; ?>

  <div class="form-container">

  <a href="my_patients.php" class="back-btn">Back to My Patients</a>

<?php if ($patientInfo): ?>
    <h2>Request Lab Test for: <?= htmlspecialchars($patientInfo['full_name']) ?></h2>
    <p>
        <strong>Gender:</strong> <?= ucfirst($patientInfo['gender']) ?> |
        <strong>DOB:</strong> <?= htmlspecialchars($patientInfo['dob']) ?> |

    </p>
<?php else: ?>
    <p class="no-data">Patient information not found.</p>
<?php endif; ?>


    <?php if (isset($_SESSION['message'])): ?>
        <div class="alert-message <?php echo isset($_SESSION['error']) ? 'error' : 'info'; ?>">
            <?= $_SESSION['message']; unset($_SESSION['message']); ?>
        </div>
    <?php endif; ?>

    <div class="selected-tests" id="selected-tests">
        <!-- Dynamically added selected tests will appear here -->
    </div>
     
    <form method="POST" class="grid-form">
      <div class="form-group">
        <label>Select Tests <span style="font-size: 13px; color: #777;">(Hold Ctrl or Cmd to select multiple)</span>:</label>
        <select name="tests[]" multiple id="test-select" required>
            <option value="Blood Test">Blood Test</option>
            <option value="Complete Blood Count (CBC)">Complete Blood Count (CBC)</option>
            <option value="Blood Sugar Test">Blood Sugar Test</option>
            <option value="HIV Test">HIV Test</option>
            <option value="Urine Test">Urinalysis</option>
            <option value="X-Ray">X-Ray</option>
            <option value="MRI">MRI</option>
            <option value="ECG">ECG</option>
            <option value="COVID-19 PCR">COVID-19 PCR</option>
            <option value="Liver Function Test">Liver Function Test</option>
            <option value="Kidney Function Test">Kidney Function Test</option>
            <option value="Thyroid Test">Thyroid Test</option>
            <option value="Pregnancy Test">Pregnancy Test</option>
        </select>
      </div>

      <div class="form-actions">
        <button type="submit">Send to Lab</button>
        <a href="my_patients.php" class="cancel-btn">Cancel</a>
      </div>
    </form>
  </div>
</div>

<script>
  // JavaScript to update the selected tests list dynamically
  const testSelect = document.getElementById('test-select');
  const selectedTestsContainer = document.getElementById('selected-tests');

  // Function to update the selected tests display
  function updateSelectedTests() {
      const selectedOptions = Array.from(testSelect.selectedOptions);
      const selectedTests = selectedOptions.map(option => option.value);
      
      // Clear the current display
      selectedTestsContainer.innerHTML = '';

      // Add each selected test as a span element
      selectedTests.forEach(test => {
          const span = document.createElement('span');
          span.textContent = test;
          // Add a click event to remove the test if needed
          span.addEventListener('click', () => removeTest(test));
          selectedTestsContainer.appendChild(span);
      });
  }

  // Function to remove a test from the selection (by clicking on it)
  function removeTest(testToRemove) {
      const options = testSelect.options;
      for (let i = 0; i < options.length; i++) {
          if (options[i].value === testToRemove) {
              options[i].selected = false;
              break;
          }
      }
      updateSelectedTests(); // Re-update the display after removal
  }

  // Attach event listener to update the display when the selection changes
  testSelect.addEventListener('change', updateSelectedTests);

  // Initialize the display on page load
  updateSelectedTests();
</script>
</body>
</html>
