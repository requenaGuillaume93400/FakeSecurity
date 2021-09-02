import Verify from "./Verify.js";

class Price extends Verify {
  constructor() {
    super();
    this.BUTTON;

    // Price for 1 hour of work BRUT in €
    this.LEAD_PRICE = 14.74;
    this.TEAM_PRICE = 10.59;
    this.SUPPORT_PRICE = 10.48;
    this.VIDEO_PRICE = 10.4;
    this.AGENT_PRICE = 10.28;
    this.DOG_PRICE = 10.59;

    // TVA & Benefices
    this.TVA = 1.2;
    this.BENEFICE = 1.12;

    // To calcul price
    this.leadDays;
    this.leadHours;
    this.teamDays;
    this.teamHours;
    this.supportNumber;
    this.supportDays;
    this.supportHours;
    this.videoNumber;
    this.videoDays;
    this.videoHours;
    this.agentNumber;
    this.agentDays;
    this.agentHours;
    this.dogNumber;
    this.dogDays;
    this.dogHours;
    this.price;
    this.superficie;
  }

  // == Main method used in main.js
  showEstimationPrice() {
    if (document.getElementById("estimation-price")) {
      this.BUTTON = document.createElement("button");
      this.BUTTON.setAttribute("id", "estimation");
      this.BUTTON.textContent = "Déclenchez l'estimation";
      document
        .getElementById("estimation-price")
        .insertAdjacentElement("afterend", this.BUTTON);

      this.price = document.getElementById("estimation-price");
      this.superficie = document.getElementById("superficie");
      this.leadDays = document.getElementById("lead-days");
      this.leadHours = document.getElementById("lead-hours");
      this.teamDays = document.getElementById("team-days");
      this.teamHours = document.getElementById("team-hours");
      this.supportNumber = document.getElementById("support-number");
      this.supportDays = document.getElementById("support-days");
      this.supportHours = document.getElementById("support-hours");
      this.videoNumber = document.getElementById("video-number");
      this.videoDays = document.getElementById("video-days");
      this.videoHours = document.getElementById("video-hours");
      this.agentNumber = document.getElementById("agent-number");
      this.agentDays = document.getElementById("agent-days");
      this.agentHours = document.getElementById("agent-hours");
      this.dogNumber = document.getElementById("dog-number");
      this.dogDays = document.getElementById("dog-days");
      this.dogHours = document.getElementById("dog-hours");

      this.BUTTON.addEventListener("click", (e) => {
        e.preventDefault();
        this.verifNumber(".obligatory:not(#toggle-spec) ~ .field >");
        this.verifOptionnalNumber();

        // Price format like " 1 299,00€ "
        this.price.textContent = `Votre devis est éstimé à : ${this.formatNumber(
          this.estimationPrice().toFixed(2)
        )} €`;
      });
    }
  }

  // == Calcul the price
  estimationPrice() {
    let values = {
      leadDays: this.leadDays.value,
      leadHours: this.leadHours.value,
      teamDays: this.teamDays.value,
      teamHours: this.teamHours.value,
      supportNumber: this.supportNumber.value,
      supportDays: this.supportDays.value,
      supportHours: this.supportHours.value,
      videoNumber: this.videoNumber.value,
      videoDays: this.videoDays.value,
      videoHours: this.videoHours.value,
      agentNumber: this.agentNumber.value,
      agentDays: this.agentDays.value,
      agentHours: this.agentHours.value,
      dogNumber: this.dogNumber.value,
      dogDays: this.dogDays.value,
      dogHours: this.dogHours.value,
    };

    // Convert empty to 0 to easyly calculate the price
    Object.values(values).forEach((value) => {
      if (value == "") {
        value = 0;
      }
      return value;
    });

    // Calculate the Production price
    let basePrice =
      values.leadDays * values.leadHours * this.LEAD_PRICE +
      values.teamDays * values.teamHours * this.TEAM_PRICE +
      values.supportNumber *
        values.supportDays *
        values.supportHours *
        this.SUPPORT_PRICE +
      values.videoNumber *
        values.videoDays *
        values.videoHours *
        this.VIDEO_PRICE +
      values.agentNumber *
        values.agentDays *
        values.agentHours *
        this.AGENT_PRICE +
      values.dogNumber * values.dogDays * values.dogHours * this.DOG_PRICE;

    // Add TVA and BENEFITS
    let finalPrice = basePrice * this.TVA;
    finalPrice = finalPrice * this.BENEFICE;

    // Prevent negative value if inputs contains errors
    if (finalPrice <= 0) {
      finalPrice = 0;
    }

    return finalPrice;
  }

  // == Format price to " 1 299,00€ "
  formatNumber(numberToFormat) {
    let chaine = String(parseFloat(numberToFormat));
    let newChaine = "";
    let longueur = chaine.length;
    let b = 0;
    let i = 0;
    let point = -1; // position of " , "
    let virgule = "00"; // default value after " , " to 00

    // Get " , " position - if value stay at -1 then no " , " detected
    point = chaine.indexOf(".", 0);

    if (point != -1) {
      virgule = chaine.substr(point + 1, longueur); // stock number after point
      chaine = chaine.substr(0, point); // delete after dot
      longueur -= longueur - point; // recalculate length
    }

    // To know when to add a space
    if (longueur % 3 != 0) {
      b = 3 - (longueur % 3);
    }

    // Add space
    for (i = 0; i < longueur; i++) {
      // if we reach 3 numbers, then we add a space
      if (b == 3) {
        newChaine += " ";
        b = 0; // reset count
      }
      b++;
      newChaine += chaine[i];
    }

    newChaine += `,${virgule}`; // add " , " and numbers after
    return newChaine;
  }
}

export default Price;
