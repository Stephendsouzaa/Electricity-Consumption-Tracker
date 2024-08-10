function searchConsumption() {
    var consumptionId = document.getElementById("consumption-search").value;
    var xhr = new XMLHttpRequest();

    // Check if consumptionId is not empty
    if(consumptionId.trim() === "") {
        alert("Please enter a consumer id.");
        return;
    }

    xhr.open("GET", "search_consumption.php?id=" + consumptionId, true);
    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4) {
            if (xhr.status == 200) {
                document.getElementById("consumption-results").innerHTML = xhr.responseText;
            } else {
                alert("Error: " + xhr.status); // Display error status
            }
        }
    };
    xhr.send();
}

    function scrollToSection(sectionId) {
        var section = document.getElementById(sectionId);
        if (section) {
            section.scrollIntoView({ behavior: 'smooth' });
        }
    }

