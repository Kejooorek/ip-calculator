let obl = document.querySelector("#oblicz");

obl.addEventListener("click", (e) => {
  let ip = document.querySelector("#ip");
  let wynik = document.querySelector(".wynik tbody");
  function addItem(name, address) {
    let n = document.createElement("tr");
    n.innerHTML = `<td>${name}</td>`;
    n.innerHTML += `<td>${address.o1.toString(2).padStart(8, "0")}.${address.o2
      .toString(2)
      .padStart(8, "0")}.${address.o3.toString(2).padStart(8, "0")}.${address.o4
      .toString(2)
      .padStart(8, "0")}</td>`;
    n.innerHTML += `<td>${address.o1}.${address.o2}.${address.o3}.${address.o4}</td>`;
    return n;
  }

  fetch(`kalkip.php?ip=${ip.value}`)
    .then((x) => x.json())
    .then((x) => {

      wynik.append(addItem("Adres IP", x.ip));
      wynik.append(addItem("Maska", x.mask));
    });
});
