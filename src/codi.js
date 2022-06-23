document.getElementById("edit").addEventListener("click", addList);

class Lista {
	constructor() {
		this.item1 = document.getElementById("item1").value;
		this.item2 = document.getElementById("item2").value;
		this.item3 = document.getElementById("item3").value;
		this.item4 = document.getElementById("item4").value;
		this.item5 = document.getElementById("item5").value;
		this.item6 = document.getElementById("item6").value;
		this.item7 = document.getElementById("item7").value;
		this.item8 = document.getElementById("item8").value;
		this.item9 = document.getElementById("item9").value;
		this.item10 = document.getElementById("item10").value;
		this.tema = document.getElementById("tema").value;
		this.titulo = document.getElementById("titulo").value;
	}
}

listaListasGeografia = [];
listaListasEsport = [];
listaListasHistoria = [];
listaListasEconomia = [];

function addList() {
	// listaListas + document.getElementById("tema").value.push(new Lista);
	var asd = [];
	var listaDeItems = document.getElementsByClassName("item");
	console.log(listaDeItems);
	for (const element of listaDeItems) {
		asd.push(element.value);
	}
	console.log(asd);

	// document.body.appendChild("ol");

	// for (let i = 0; i < 10; i++) {
	// 	document.getElementsByTagName("ol").appendChild(li);
	// 	document.getElementsByTagName("ol").nthChild(i)=document.getElementById("item" + i).innerHTML;
	// }

	$.ajax({
		url: "./codi.php",
		type: "POST",
		data: {
			api: "insertarItems",
			tema: document.getElementById("tema").value,
			titulo: document.getElementById("titulo").value,
			item0: asd[0],
			item1: asd[1],
			item2: asd[2],
			item3: asd[3],
			item4: asd[4],
			item5: asd[5],
			item6: asd[6],
			item7: asd[7],
			item8: asd[8],
			item9: asd[9],
		},
		// dataType: "json",
		success: function (response) {
			if (response == 0) {
				console.warn(response);
			} else {
				console.log(response);
				if ("error" in response) {
					console.warn("ERROR");
				} else {
					console.warn("OK");
				}
			}
		},
		error: function (error) {
			console.warn("ERROR:" + error);
		},
	});
}
