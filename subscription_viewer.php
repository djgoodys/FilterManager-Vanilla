<?php
if (isset($_POST['delete_file'])) {
    // Get the filename from the dropdown list
    $filename = $_POST['subscription-file-select'];

    // Validate filename to prevent potential security issues
    if (!empty($filename) && preg_match('/^[a-zA-Z0-9\-_.]+\.txt$/', $filename)) {
        // Construct the full path to the file
        $filePath = 'subscriptions/' . $filename;

        // Check if the file exists before attempting deletion
        if (file_exists($filePath)) {
            if (unlink($filePath)) {
                echo "File deleted successfully.";  // Display success message
            } else {
                echo "Error deleting file.";  // Display error message if deletion fails
            }
        } else {
            echo "File not found.";  // Display message if file doesn't exist
        }
    } else {
        echo "Invalid filename.";  // Display message for invalid filename
    }
}

$subscription_files = scandir("subscriptions");
$subscription_files = array_diff($subscription_files, array('.', '..'));
if (empty($subscription_files)) {
  echo "No subscription files found in the 'subscriptions' folder.";
} else {
?>
<html>
<body>
  <h2>Subscription Viewer</h2>
  <form method="post" action="<?php echo $_SERVER["SCRIPT_NAME"] ?>">
  <select id="subscription-file-select" name="subscription-file-select">
  <option>select file</option>
    <?php foreach ($subscription_files as $file) : ?>
    <option value="<?php echo $file; ?>"><?php echo $file; ?></option>
    <?php endforeach; ?>
  </select>
  <button type="submit" name="delete_file">Delete File</button>
  </form>
  <br><br>
  <pre id="subscription-data" style="white-space: pre-wrap;"></pre>

  <script>
    // Get a reference to the dropdown and data container
    const fileSelect = document.getElementById("subscription-file-select");
    const dataPre = document.getElementById("subscription-data");

    // Add an event listener to the dropdown
    fileSelect.addEventListener("change", function() {
      const selectedFile = fileSelect.value;

      // Fetch the file contents using AJAX
      fetch("subscriptions/" + selectedFile)
        .then(response => response.text())
        .then(data => {
          // Replace spaces with newlines in the data
          const formattedData = data.replace(/ /g, "\n");

          // Update the data container with formatted data
          dataPre.textContent = formattedData;
        })
        .catch(error => {
          console.error("Error fetching file:", error);
          dataPre.textContent = "Error: Could not load file content.";
        });
    });
  </script>
</body>
</html>
<?php
}
?>
