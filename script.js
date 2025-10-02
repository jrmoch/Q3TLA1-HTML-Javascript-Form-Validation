
  // Function to send form data to save.php
  function saveToPhp(formData) {
    fetch('save.php', {
      method: 'POST',
      body: formData
    })
    .then(response => response.text())
    .then(data => {
      console.log("Server response:", data);
      // Optionally show a confirmation message in the summary
      const confirmation = `<p style="color:green;"><em>Data successfully saved to server.</em></p>`;
      document.getElementById('summaryContent').insertAdjacentHTML('beforeend', confirmation);
    })
    .catch(error => {
      console.error("Error saving data:", error);
      const errorMsg = `<p style="color:red;"><em>There was a problem saving your data.</em></p>`;
      document.getElementById('summaryContent').insertAdjacentHTML('beforeend', errorMsg);
    });
  }

  document.getElementById('enrollmentForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const formData = new FormData(this);
    let summaryHTML = '';

    for (let [key, value] of formData.entries()) {
      if (key !== 'password' && key !== 'confirm_password' && key !== 'photo') {
        const label = key.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
        summaryHTML += `<p><strong>${label}:</strong> ${value}</p>`;
      }
    }

    // Handle photo preview
    const photoInput = document.getElementById('photo');
    if (photoInput.files && photoInput.files[0]) {
      const reader = new FileReader();
      reader.onload = function(e) {
        summaryHTML += `<p><strong>Photo:</strong><br>
                          <img src="${e.target.result}" alt="Uploaded Photo" 
                               style="max-width:150px; border-radius:6px; margin-top:8px;">
                        </p>`;
        document.getElementById('summaryContent').innerHTML = summaryHTML;
      };
      reader.readAsDataURL(photoInput.files[0]);
    } else {
      document.getElementById('summaryContent').innerHTML = summaryHTML;
    }

    // Hide the form
    this.style.display = 'none';

    // Show the summary
    document.getElementById('summary').style.display = 'block';

    // Save data to PHP
    saveToPhp(formData);
  });

