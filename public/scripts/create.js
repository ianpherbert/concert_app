let i = setInterval(() => {
  let form = document.getElementById("form").querySelectorAll("div.mb-3");

  form.forEach((e) => {
    e.hidden = true;
    try {
      e.querySelector("input").value = "";
    } catch {}
  });

  document.getElementById("formWrapper").hidden = false;

  let n = 0;

  form[n].hidden = false;
  form[n].querySelector("input").focus();
  // form[n].value = "";

  document.getElementById("next").addEventListener("click", (e) => {
    console.log(n);
    form[n].hidden = true;
    n++;
    form[n].hidden = false;
    try {
      form[n - 1].querySelector("input").blur();
      form[n].querySelector("input").focus();
    } catch {}
    check();
  });

  document.getElementById("prev").addEventListener("click", (e) => {
    document.getElementById("next").hidden = false;
    document.getElementById("next").hidden = false;
    form[n].hidden = true;
    n--;
    form[n].hidden = false;
    try {
      form[n + 1].querySelector("input").blur();
      form[n].querySelector("input").focus();
    } catch {}
    check();
  });

  function check() {
    if (n > 0) {
      document.getElementById("prev").disabled = false;
    } else {
      document.getElementById("prev").disabled = true;
    }
    if (n < form.length - 1) {
      document.getElementById("next").hidden = false;
      document.getElementById("info").hidden = true;
    } else {
      document.getElementById("next").hidden = true;
      let list = document.getElementById("info");
      list.innerHTML = "";
      form.forEach((item) => {
        if (
          item.querySelector("input") != null &&
          item.querySelector("input").value != ""
        ) {
          li = document.createElement("li");

          li.innerHTML =
            "<p>" + item.querySelector("label").textContent + "</p> ";
          li.innerHTML += "<p>" + item.querySelector("input").value + "</p> ";
          list.appendChild(li);
        }
      });
      document.getElementById("info").hidden = false;
    }
  }

  document.addEventListener("keypress", (e) => {
    if (e.key == "Enter") {
      e.preventDefault();

      form[n].hidden = true;
      n++;
      form[n].hidden = false;
      try {
        form[n - 1].querySelector("input").blur();
        form[n].querySelector("input").focus();
      } catch {}
      check();
    }
  });

  document.querySelectorAll("input").forEach((i) => {
    i.addEventListener("click", (e) => {
      e.currentTarget.value = "";
    });
  });

  clearInterval(i);
});
