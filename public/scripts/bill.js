let check = setInterval(() => {
  if (window.history.replaceState) {
    window.history.replaceState(null, null, window.location.href);
  }

  const bands = Array.from(
    document.getElementById("bill").querySelectorAll("span")
  );

  bands.forEach((band) => {
    let selects = document
      .getElementById("form_band")
      .querySelectorAll("option");
    try {
      let found = Array.from(selects).findIndex(
        (s) => s.textContent == band.textContent
      );
      selects[found].remove();
    } catch {
      // alert("There was an error...");
    }
  });

  document.getElementById("search_button").addEventListener("click", () => {
    search();
  });
  document.getElementById("search").addEventListener("keyup", (e) => {
    if (e.key == "Enter") {
      search();
    }
  });

  clearInterval(check);
}, 200);

function search() {
  let selects = document.getElementById("form_band");

  const term = document.getElementById("search").value;
  fetch(`/api/bands/${term}`)
    .then(function (response) {
      return response.json();
    })
    .then((response) => {
      selects.innerHTML = "";
      response.forEach((band) => {
        let option = document.createElement("option");
        option.textContent = band.name + " (" + band.city + ")";
        option.value = band.id;
        selects.appendChild(option);
      });
    });
}
