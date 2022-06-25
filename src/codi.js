document.getElementById("edit").addEventListener("click", addList);
getTemas();

function addList() {
	var asd = [];
	var listaDeItems = document.getElementsByClassName("item");
	console.log(listaDeItems);
	for (const element of listaDeItems) {
		asd.push(element.value);
	}
	console.log(asd);

	$.ajax({
		url: "/src/codi.php",
		type: "POST",
		//dataType: "json",
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
		success: function (response) {
			console.log(response);
		},
		error: function (error) {
			console.warn("ERROR 2:");
			console.warn(error);
		},
	});
}

function getTemas() {
	$.ajax({
		url: "/src/codi.php",
		type: "POST",
		data: {
			api: "getTemas",
			// tema: document.getElementById("tema").value,
			// titulo: document.getElementById("titulo").value,
		},
		dataType: "json",
		success: function (response) {
			if (response == 0) {
				console.warn(response);
			} else {
				console.log(response);
				if ("error" in response) {
					console.warn("ERROR 1:");
				} else {
					var selectTema = document.getElementById("tema");
					response.forEach((element) => {
						//  console.log(element);
						var option = document.createElement("option");
						option.innerHTML = element["tema"];
						option.value = element["tema"];
						selectTema.appendChild(option);
					});
				}
			}
		},
		error: function (error) {
			console.warn("ERROR 2:");
			console.warn(error);
		},
	});
}

getTitulos();
function getTitulos() {
	$.ajax({
		url: "/src/codi.php",
		type: "POST",
		data: {
			api: "getTitulos",
			tema: "Geografia",
			// titulo: document.getElementById("titulo").value,
		},
		dataType: "json",
		success: function (response) {
			if (response == 0) {
				console.warn(response);
			} else {
				console.log(response);
				if ("error" in response) {
					console.warn("ERROR 1:");
				} else {
					// var selectTema = document.getElementById("tema");
					response.forEach((element) => {
						//  console.log(element);
						//  console.log(JSON.parse(element));
						console.log(element);
						//   var option = document.createElement("option");
						//   option.innerHTML = element["tema"];
						//   option.value = element["tema"];
						//   selectTema.appendChild(option);
					});
				}
			}
		},
		error: function (error) {
			console.warn("ERROR 2:");
			console.warn(error);
		},
	});
}
