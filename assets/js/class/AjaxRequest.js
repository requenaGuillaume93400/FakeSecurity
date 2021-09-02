class AjaxRequest {
  handleOperation() {
    this.operation("#clients");
    this.operation("#orders");
    this.operation("#agents");
    this.operation("#sanctions");
  }

  operation(tableId) {
    let buttons = document.querySelectorAll(tableId + " tbody tr td a");
    if (buttons) {
      buttons.forEach((button) => {
        button.addEventListener("click", function (e) {
          if (/delete|confirm|upgrade/.test(button.id)) {
            e.preventDefault();

            // Delete/Confirm without refreshing page
            let xhttp = new XMLHttpRequest();
            xhttp.open("GET", `backoffice-${button.id}`, true);
            xhttp.send();

            // Hide the targeted table row or button
            xhttp.onreadystatechange = function () {
              if (this.readyState == 4 && this.status == 200) {
                if (/delete/.test(button.id)) {
                  let tableRow = button.parentElement.parentElement;
                  tableRow.style.display = "none";
                } else if (/confirm|upgrade/.test(button.id)) {
                  button.style.display = "none";
                }
              }
            };
          }
        });
      });
    }
  }

  handleEdit() {
    this.edit("clients");
    this.edit("agents");
  }

  edit(formId) {
    let form = document.getElementById(formId);
    if (form) {
      form.addEventListener("submit", onSubmitForm);
      async function onSubmitForm(e) {
        e.preventDefault();

        const inputDom = this.querySelectorAll("input");
        const siteType = document.getElementById("type");

        let formData = new FormData();

        if (siteType) {
          formData.append(siteType.getAttribute("name"), siteType.value);
        }

        inputDom.forEach((input) => {
          formData.append(input.getAttribute("name"), input.value);
        });

        let req = new Request(this.getAttribute("action"), {
          method: "POST",
          body: formData,
        });

        let response;
        response = await fetch(req).catch((err) =>
          console.log("Une erreur est survenue")
        );

        if (!response.ok) {
          alert("Une erreur est survenue");
          return;
        }

        alert("Les modifications ont bien été prises en compte.");
      }
    }
  }
}

export default AjaxRequest;
