let i = setInterval(() => {
  setPage();
  clearInterval(i);
});

function setPage() {
  let choose = document.getElementById("typeChoice");

  let roleDiv = document.querySelector("select").parentNode;
  roleDiv.parentNode.appendChild(choose);
  roleDiv.hidden = true;

  let choices = choose.querySelectorAll("div");
  choices.forEach((c) => {
    c.addEventListener("click", (e) => {
      choices.forEach((choice) => {
        choice.classList.remove("active");
        choice.classList.add("inactive");
      });

      console.log(e.currentTarget);
      document.querySelector("select").value = e.currentTarget.id;
      e.currentTarget.classList.remove("inactive");
      e.currentTarget.classList.add("active");
    });
  });
}
